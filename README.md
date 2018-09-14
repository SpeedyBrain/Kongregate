## Kongregate API
This is a simple helper to communicate with the [Kongregate Server Side API](https://docs.kongregate.com/docs/server-side-http) via PHP.

### Usage
The [Kongregate](src/SpeedyBrain/Kongregate/Kongregate.php) Class has a static function for every Server Side API Endpoint, so there is no need to create an object. Every function will return an instance of [HttpResponse](src/SpeedyBrain/Kongregate/HttpResponse.php)

**Example 'High Scores'**
```PHP
use SpeedyBrain\Kongregate\Kongregate;

$response = Kongregate::highscores('weekly',22544);

if($response->success())
{
  $highscores = $response->parsedBody();
}
```
