<?php
/**
 * checkout.php
 * - 사용자가 이 페이지에 접속하면 Stripe Checkout 세션을 생성하고
 *   Stripe 결제 페이지로 바로 redirect 합니다.
 */
require_once 'config.php';

// Stripe Checkout Session 생성
$ch = curl_init('https://api.stripe.com/v1/checkout/sessions');

// Checkout 세션 파라미터
$params = array(
    'mode'        => 'payment',
    'success_url' => BASE_URL . '/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url'  => BASE_URL . '/cancel.php',

    // 상품 정보: Stripe Price ID 사용
    'line_items[0][price]'    => PRODUCT_PRICE_ID,
    'line_items[0][quantity]' => 1,
);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . STRIPE_SECRET_KEY,
));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($response === false) {
    $error = curl_error($ch);
    curl_close($ch);
    die('Stripe API 호출 오류: ' . htmlspecialchars($error));
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$session = json_decode($response, true);

if ($httpCode !== 200 || !is_array($session) || !isset($session['url'])) {
    // Stripe에서 에러가 온 경우
    echo "<h1>결제 세션 생성 중 오류가 발생했습니다.</h1>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    exit;
}

// Stripe Checkout 페이지로 리다이렉트
header('Location: ' . $session['url']);
exit;
?>
