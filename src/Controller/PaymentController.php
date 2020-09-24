<?php

namespace App\Controller;

use App\DTO\PaymentDto;
use App\DTO\PaymentFormDto;
use App\Entity\Payment;
use App\Form\PaymentForm;
use App\Handler\PaymentHandler;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Predis\Client as RedisClient;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Swagger\Annotations as SWG;

class PaymentController extends AbstractController
{
    /**
     * @var PaymentHandler
     */
    private $handler;

    /**
     * @var RedisClient
     */
    private $redis;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ProducerInterface
     */
    private $shopPaymentProducer;

    /**
     * PaymentController constructor.
     * @param PaymentHandler $handler
     * @param RedisClient $redis
     * @param EntityManagerInterface $em
     */
    public function __construct(
        PaymentHandler $handler,
        RedisClient $redis,
        EntityManagerInterface $em,
        ProducerInterface $shopPaymentProducer
    )
    {
        $this->handler = $handler;
        $this->redis = $redis;
        $this->em = $em;
        $this->shopPaymentProducer = $shopPaymentProducer;
    }

    /**
     *
     * Register payment.
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns the payments by date",
     *     @SWG\Schema(
     *        type="array",
     *        @SWG\Items(ref=@Model(type=Payment::class, groups={"full"}))
     *     )
     * )
     * @SWG\Post(
     *     consumes={"multipart/form-data"},
     *     @SWG\Parameter(
     *     name="amount",
     *     in="formData",
     *     type="number",
     *     description="Amount of payment",
     *     required=true
     *      ),
     *      @SWG\Parameter(
     *     name="purpose",
     *     in="formData",
     *     type="string",
     *     description="Purpose of payment",
     *     required=true
     *      ),
     *     @SWG\Parameter(
     *     name="shopUr;",
     *     in="formData",
     *     type="string",
     *     description="Shop url",
     *     required=true
     *      )
     * )
     * @SWG\Tag(name="payments")
     *
     * @Route("/api/register", name="payment_register", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request): Response
    {
        $dto = new PaymentDto($request->request->get('amount'), $request->request->get('purpose'), $request->request->get('shopUrl'));
        $id = $this->handler->registerPayment($dto);

        $dto->url = $this->generateUrl('payment_form', ['sessionId' => $id], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($dto, Response::HTTP_CREATED);
    }

    /**
     *
     * Create form and do payment.
     *
     * @Route("/api/payments/card/form", name="payment_form", methods={"GET", "POST"}, requirements={"sessionId"="\d+"})
     *
     * @param Request $request
     * @return Response
     */
    public function getPaymentFormAction(Request $request): Response
    {
        $id = $request->query->get('sessionId');
        $redisKey = 'payments:'.$id;

        $payment = $this->redis->hgetall($redisKey);

        if (empty($payment)) {
            throw $this->createNotFoundException('No payment found for id' . $id);
        }

        $paymentFormDto = new PaymentFormDto($payment);

        $form = $this->createForm(PaymentForm::class, $paymentFormDto);

        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $this->shopPaymentProducer->publish(json_encode(['sessionId' => $id, 'shopUrl' => $payment['shopUrl']]));
            return new JsonResponse(['status' => 'ok'], Response::HTTP_OK);
        }

        return $this->render('payment/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     *
     * List all payments by period.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the payments by date",
     *     @SWG\Schema(
     *        type="array",
     *        @SWG\Items(ref=@Model(type=Payment::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="from",
     *     in="query",
     *     type="string",
     *     description="The field used to set start date",
     *     @SWG\Schema(type="string")
     * )
     * @SWG\Parameter(
     *     name="to",
     *     in="query",
     *     type="string",
     *     description="The field used to set end date",
     *     @SWG\Schema(type="string")
     * )
     * @SWG\Tag(name="payments")
     *
     * @Route("/api/payments", name="find_payments_by_datetime", methods={"GET"}, requirements={"from"="\d+", "to"="\d+"})
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function getPayments(Request $request): JsonResponse
    {
        $from = \DateTime::createFromFormat('Y-m-d', $request->query->get('from'));
        $to = \DateTime::createFromFormat('Y-m-d', $request->query->get('to'));
        /** @var PaymentRepository $paymentRepository */
        $payments = $this->em->getRepository(Payment::class)->findPaymentsByDateTime($from, $to);

        if (!empty($paymentRepository)) {
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers);
            $payments = $serializer->normalize($payments);
        }

        return new JsonResponse($payments, Response::HTTP_OK);
    }
}
