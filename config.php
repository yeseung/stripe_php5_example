<?php
/**
 * Stripe 기본 설정 파일 (PHP5용)
 */

// Stripe Secret Key (Dashboard > Developers > API keys)
define('STRIPE_SECRET_KEY', 'sk_test_your_secret_key_here');

// 사이트 기본 URL (마지막 슬래시 제거) – 환경에 맞게 수정
// 예: https://kimtaja.com/stripe
define('BASE_URL', 'https://your-site.com/stripe');

// 상품 정보 (Stripe Dashboard에서 만든 Price ID 사용)
define('PRODUCT_NAME', '광고 제거 연간 이용권');
// Stripe Price ID (예: price_1Pxxxxxxx)
define('PRODUCT_PRICE_ID', 'price_your_price_id_here');
?>
