# SeriaCAPTCHA PHP SDK

## English

### Installation

```bash
composer require seriacaptcha/sdk
```

### Usage Example

```php
require 'vendor/autoload.php';

use SeriaCAPTCHA\SeriaCAPTCHA;

$sdk = new SeriaCAPTCHA('https://hk.seriacaptcha.com');
try {
    $result = $sdk->verify(
        'your_appid',
        'user_token',
        'your_secret',
        'user_real_ip',      // optional
        'user_agent_string'  // optional
    );
    if ($result['code'] === 1) {
        // Verification passed
    } else {
        // Verification failed
    }
} catch (Exception $e) {
    // Network or API error
    echo $e->getMessage();
}
```

### Endpoint Validation

- Only root domains ending with `.seriacaptcha.com` are allowed as endpoint.
- You only need to pass the root domain, SDK will automatically append `/verify.php`.
- If the endpoint is invalid, the constructor will throw an exception.

---

## 正體中文

### 安裝

```bash
composer require seriacaptcha/sdk
```

### 使用範例

```php
require 'vendor/autoload.php';

use SeriaCAPTCHA\SeriaCAPTCHA;

$sdk = new SeriaCAPTCHA('https://hk.seriacaptcha.com');
try {
    $result = $sdk->verify(
        '你的appid',
        '用戶token',
        '你的secret',
        '用戶真實IP',      // 可選
        '用戶UA字串'       // 可選
    );
    if ($result['code'] === 1) {
        // 驗證通過
    } else {
        // 驗證失敗
    }
} catch (Exception $e) {
    // 網路或API異常
    echo $e->getMessage();
}
```

### endpoint 驗證機制

- 只允許以 `.seriacaptcha.com` 結尾的根網域作為 endpoint。
- endpoint 只需傳入根網域，SDK 會自動拼接 `/verify.php`。
- 若 endpoint 非法，建構子會丟出例外。 