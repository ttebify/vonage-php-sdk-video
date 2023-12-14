# Migration guide from OpenTok PHP SDK to Vonage Video PHP SDK

The change from the OpenTok repository to the Vonage namespace requires some migration instructions, detailed
here. Assuming that you are using only the Video API, you will need to take the following actions:

## Uninstall OpenTok PHP using Composer

```bash
$ composer remove opentok/opentok
```
All calls to the video client will now fail, so install the necessary replacements:

```bash
$ composer install vonage/client-core
$ composer install vonage/video
```

> Please note that you *must* install both packages.

## Replacement Instantiation of the Vonage Client Class

The following lines of code will boot the new Unified Client:

```php
$credentials = new \Vonage\Client\Credentials\Keypair(YOUR_PRIVATE_KEY_PATH, APPLICATION_ID);
$client = new \Vonage\Client($credentials);
$videoClient = $client->video();
```

The `$videoClient` is now your replacement OpenTok Client, with the same function calls to talk to the API.

### Compatibility

| API                       | Supported? |
|---------------------------|:----------:|
| Session Creation          |     ✅      |
| Signaling                 |     ✅      |
| Force Muting              |     ✅      |
| Archiving                 |     ✅      |
| Custom S3/Azure buckets   |     ❌      |
| SIP Interconnect          |     ❌      |
| Live Streaming Broadcasts |     ❌      |
| Experience Composer       |     ❌      |
| Account Management        |     ❌      |