<?php
require_once 'config.php';

$session_id = isset($_GET['session_id']) ? $_GET['session_id'] : '';

if ($session_id === '') {
    die('유효하지 않은 접근입니다. (session_id 없음)');
}

// Checkout Session 조회
$url = 'https://api.stripe.com/v1/checkout/sessions/' . urlencode($session_id);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . STRIPE_SECRET_KEY,
));
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

if ($httpCode !== 200 || !is_array($session)) {
    echo "<h1>결제 정보 조회 중 오류가 발생했습니다.</h1>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    exit;
}

// 상태/금액 확인
$payment_status = isset($session['payment_status']) ? $session['payment_status'] : 'unknown';
$amount_total   = isset($session['amount_total']) ? $session['amount_total'] : 0; // 센트 단위
$currency       = isset($session['currency']) ? strtoupper($session['currency']) : '';

// TODO: 여기서 DB 처리 (회원 광고제거 활성화 등)
if ($payment_status === 'paid') {
    // 예시:
    // 1. $session['client_reference_id'] 나 metadata로 회원 ID 넘겨놨다면 읽어오기
    // 2. orders 테이블에 로그 기록
    // 3. member 테이블에 adfree 만료일 +1년 업데이트
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>결제 완료</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Malgun Gothic", sans-serif; padding: 40px; }
        .box { max-width: 480px; margin: 0 auto; border: 1px solid #ccc; padding: 24px; border-radius: 8px; }
    </style>
</head>
<body>
<div class="box">
    <h1>결제가 완료되었습니다.</h1>
    <p>결제 상태: <strong><?php echo htmlspecialchars($payment_status); ?></strong></p>
    <p>결제 금액: <strong><?php echo htmlspecialchars($amount_total / 100.0) . ' ' . htmlspecialchars($currency); ?></strong></p>
    <p>이제 광고 제거 기능이 활성화됩니다. (실제 서비스에서는 DB에 반영해 주세요.)</p>
</div>
</body>
</html>
