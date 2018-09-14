<?php
namespace SpeedyBrain\Kongregate;

class Kongregate
{
    /**
     * Kongregate base API url.
     * @var string
     */
    const KONG_API_URL = 'https://api.kongregate.com/';

    /**
     * Verifies a user's identity
     * https://docs.kongregate.com/docs/server-api-authenticate
     * @param  int    $userId        Kongregate User ID
     * @param  string $gameAuthToken Game Authentication Token
     * @param  string $gameApiKey    Your Private API Key
     * @return HttpResponse          Http Response
     */
    public static function authenticate(int $userId, string $gameAuthToken, string $gameApiKey)
    {
      $query = [
        "api_key" => $gameApiKey,
        "user_id" => $userId,
        "game_auth_token" => $gameAuthToken
      ];
      return self::curl(self::KONG_API_URL.'api/authenticate.json','GET', "", $query);
    }

    /**
     * Retrieves badge definitions
     * https://docs.kongregate.com/docs/server-api-badges
     * @return HttpResponse        Http Response
     */
    public static function badges()
    {
      return self::curl(self::KONG_API_URL.'badges.json','GET');
    }

    /**
     * Retrieves information about a user's badges
     * https://docs.kongregate.com/docs/server-api-user-badges
     * @param  string $userName Username of the user to retrieve badges for
     * @return HttpResponse        Http Response
     */
    public static function badgesUser(string $userName)
    {
      return self::curl(self::KONG_API_URL.'accounts/'.$userName.'/badges.json','GET');
    }

    /**
     * Retrieve high scores for a statistic
     * https://docs.kongregate.com/docs/server-api-high-scores
     * @param  string  $scope       daily, weekly, or lifetime
     * @param  int     $statisticId Statistic ID to retrieve scores for
     * @param  int     $page        Optional page number
     * @return HttpResponse         Http Response
     */
    public static function highscores(string $scope, int $statisticId, int $page = 1)
    {
      switch ($scope) {
        case 'daily':
          $query = ["today_page"=>$page];
          break;
        case 'weekly':
          $query = ["weekly_page"=>$page];
          break;
        case 'lifetime':
          $query = ["lifetime_page"=>$page];
          break;
        default:
          $query = [];
          break;
      }
      return self::curl(self::KONG_API_URL.'api/high_scores/'.$scope.'/'.$statisticId.'.json','GET','',$query);
    }

    /**
     * Creates/updates a guild definition
     * https://docs.kongregate.com/docs/server-api-create-guild
     * @param  string $gameApiKey Your private API key
     * @param  string $guildId    Unique (per-server) identifier for the guild
     * @param  string $guildName  Display name for the guild
     * @param  string $serverId   The unique identifier for the server
     * @param  string $serverName The display name for the server
     * @return HttpResponse       Http Response
     */
    public static function guildsCreate(string $gameApiKey, string $guildId, string $guildName, string $serverId = "Default", string $serverName = "Default")
    {
      $headers = ["Content-type: application/json"];
      $body = [
        "api_key" => $gameApiKey,
        "server_identifier" => $serverId,
        "server_name" => $serverName,
        "guild_identifier" => $guildId,
        "guild_name" => $guildName
      ];
      return self::curl(self::KONG_API_URL.'api/guilds.json', 'POST', json_encode($body), [], $headers);
    }

    /**
     * Destroys a guild definition
     * https://docs.kongregate.com/docs/server-api-destroy-guild
     * @param  string $gameApiKey Your private API key
     * @param  string $guildId    Unique (per-server) identifier for the guild
     * @param  string $serverId   The unique identifier for the server
     * @return HttpResponse       Http Response
     */
    public static function guildsDestroy(string $gameApiKey, string $guildId, string $serverId = "Default")
    {
      $headers = ["Content-type: application/json"];
      $body = [
        "api_key" => $gameApiKey,
        "server_identifier" => $serverId,
        "guild_identifier" => $guildId
      ];
      return self::curl(self::KONG_API_URL.'api/guilds.json', 'POST', json_encode($body), [], $headers);
    }

    /**
     * Creates/manages game characters for use with guilds
     * https://docs.kongregate.com/docs/server-api-characters
     * @param  string $gameApiKey      Your private API key
     * @param  string $characterId     Unique (per-server) identifier for the user
     * @param  string $characterName   Display name for the user
     * @param  string $serverId        Unique identifier for the server the guild exists on
     * @param  string $serverName      Display name for the server the guild exists on
     * @param  string $guildId         Unique (per-server) identifier for the guild
     * @param  string $guildName       Display name for the guild
     * @param  int    $userId          The Kongregate user id (if any) of the user
     * @param  string $guildAdminLevel Set to superadmin or admin to grant privileges
     * @return HttpResponse            Http Response
     */
    public static function guildsCharacters(string $gameApiKey, string $characterId, string $characterName, string $serverId = "Default", string $serverName = "Default", string $guildId = "", string $guildName = "", int $userId = null, string $guildAdminLevel = "")
    {
      $headers = ["Content-type: application/json"];
      $body = [
        "api_key" => $gameApiKey,
        "character_identifier" => $characterId,
        "character_name" => $characterName,
        "server_identifier" => $serverId,
        "server_name" => $serverName
      ];
      if(!empty($guildId))          {$body["guild_identifier"]=$guildId;}
      if(!empty($guildName))        {$body["guild_name"]=$guildName;}
      if(!empty($userId))           {$body["user_id"]=$userId;}
      if(!empty($guildAdminLevel))  {$body["guild_admin_level"]=$guildAdminLevel;}
      return self::curl(self::KONG_API_URL.'api/characters.json', 'POST', json_encode($body), [], $headers);
    }

    /**
     * Retrieve a list of high scores for a user's friends
     * https://docs.kongregate.com/docs/server-api-friends-high-scores
     * @param  int    $statisticId Statistic ID to retrieve scores for
     * @param  int    $userId      Kongregate user ID of the user to retrieve friend's scores for
     * @return HttpResponse        Http Response
     */
    public static function highscoresFriends(int $statisticId, int $userId)
    {
      return self::curl(self::KONG_API_URL.'api/high_scores/friends/'.$statisticId.'/'.$userId.'.json','GET');
    }

    /**
     * Retrieves static item definitions from the server
     * https://docs.kongregate.com/docs/server-api-item-list
     * @param  string $gameApiKey Your private API key
     * @param  array  $tags       Array of tags to filter by
     * @return HttpResponse       Http Response
     */
    public static function items(string $gameApiKey, array $tags = [])
    {
      $query = ["api_key" => $gameApiKey];
      if(!empty($tags)){
        $query["tags"] = implode(",", $tags);
      }
      return self::curl(self::KONG_API_URL.'api/items.json','GET','',$query);
    }

    /**
     * Retrieve a user's inventory
     * https://docs.kongregate.com/docs/server-api-user-items
     * @param  string $gameApiKey Your private API key
     * @param  int    $userId     Kongregate User ID of the user you wish to retrieve items for
     * @return HttpResponse       Http Response
     */
    public static function itemsUser(string $gameApiKey, int $userId)
    {
      $query = [
        "api_key" => $gameApiKey,
        "user_id" => $userId
      ];
      return self::curl(self::KONG_API_URL.'api/user_items.json','GET','',$query);
    }

    /**
     * Use a consumable item from a user's inventory
     * https://docs.kongregate.com/docs/server-api-use-item
     * @param  string $gameApiKey    Your private API key
     * @param  int    $userId        The Kongregate user ID of the owner of the item
     * @param  string $gameAuthToken The game auth token for the user
     * @param  int    $itemId        The item instance ID]
     * @return HttpResponse          Http Response
     */
    public static function itemsUserConsume(string $gameApiKey, int $userId, string $gameAuthToken, int $itemId)
    {
      $headers = ["Content-type: application/json"];
      $body = [
        "api_key" => $gameApiKey,
        "user_id" => $userId,
        "game_auth_token" => $gameAuthToken,
        "id" => $itemId
      ];
      return self::curl(self::KONG_API_URL.'api/use_item.json', 'POST', json_encode($body), [], $headers);
    }

    /**
     * Retrieve information about Kongpanions
     * https://docs.kongregate.com/docs/server-api-kongpanions
     * @return HttpResponse  Http Response
     */
    public static function kongpanions()
    {
      return self::curl(self::KONG_API_URL.'api/kongpanions/index.json','GET');
    }

    /**
     * Retrieve the list of Kongpanions a user owns
     * https://docs.kongregate.com/docs/server-api-user-kongpanions
     * @param  string $userName The username of the user to retrieve Kongpanions for
     * @param  int $userId      The user id of the user to retrieve Kongpanions for
     * @return HttpResponse     Http Response
     */
    public static function kongpanionsUser(string $userName = "", int $userId = null)
    {
      $query = [];
      if($userName !== ""){$query["username"] = $userName;}
      if($userId !== null){$query["user_id"] = $userId;}
      return self::curl(self::KONG_API_URL.'api/kongpanions.json','GET', '', $query);
    }

    /**
     * Send messages to Kongregate users
     * The $userId and $userName parameters are optional, but you must include one or the other.
     * https://docs.kongregate.com/docs/server-api-private-message
     * @param  string $gameApiKey Your private api key
     * @param  string $content    The text content to send to the user. Any HTML will be stripped, though bold and italics will be rendered.
     * @param  int    $userId     User id of the recipient of the message
     * @param  string $userName   Username of the recipient of the message
     * @param  string $imageUrl   An image to be included with the message
     * @return HttpResponse       Http Response
     */
    public static function privateMessage(string $gameApiKey, string $content, int $userId = null, string $userName = "", string $imageUrl = "")
    {
      $headers = ["Content-type: application/json"];
      $body = [
        "api_key" => $gameApiKey,
        "content" => $content
      ];
      if($userId !== null){$body["user_id"] = $userId;}
      if($userName !== ""){$body["username"] = $userName;}
      if($imageUrl !== ""){$body["image_url"] = $imageUrl;}
      return self::curl(self::KONG_API_URL.'api/private_message.json','POST', json_encode($body), [], $headers);
    }

    /**
     * Create a shared link
     * https://docs.kongregate.com/docs/server-api-create-shared-link
     * @param  string $gameApiKey    Your private API key
     * @param  int    $userId        User id of the user
     * @param  string $gameAuthToken The game auth token for the game/user combination
     * @param  string $id            Unique identifier of the event in your system
     * @param  string $name          Name of the link
     * @param  string $type          A type or category for the link that can be used for filtering
     * @param  string $linkParams    URL-encoded parameters to pass into the game frame. Only parameters starting with kv_ will be passed through
     * @param  int    $expiration    UNIX timestamp for when the link should no longer be shown to users
     * @param  string $kvParams      Optional JSON encoded string of an object containing extra information about the link
     * @return HttpResponse          Http Response
     */
    public static function sharedLinkCreate(string $gameApiKey, int $userId, string $gameAuthToken, string $id, string $name, string $type, string $linkParams, int $expiration, string $kvParams="")
    {
      $headers = ["Content-type: application/json"];
      $body = [
        "api_key" => $gameApiKey,
        "user_id" => $userId,
        "game_auth_token" => $gameAuthToken,
        "id" => $id,
        "name" => $name,
        "type" => $type,
        "link_params" => $linkParams,
        "expiration" => $expiration
      ];
      if($kvParams !== ""){$body["kv_params"] = $kvParams;}
      return self::curl(self::KONG_API_URL.'api/shared_links/create.json','POST', json_encode($body), [], $headers);
    }

    /**
     * Destroy a shared link
     * https://docs.kongregate.com/docs/server-api-destroy-shared-link
     * @param  string $id             Unique identifier of the link in your system
     * @param  string $gameApiKey     Your private API key
     * @param  int    $userId         User id of the user
     * @param  string $gameAuthToken  The game auth token for the game/user combination
     * @return HttpResponse           Http Response
     */
    public static function sharedLinkDestroy(string $id, string $gameApiKey, int $userId, string $gameAuthToken)
    {
      $headers = ["Content-type: application/json"];
      $body = [
        "api_key" => $gameApiKey,
        "user_id" => $userId,
        "game_auth_token" => $gameAuthToken
      ];
      return self::curl(self::KONG_API_URL.'api/shared_links/'.$id.'/destroy.json','POST', json_encode($body), [], $headers);
    }

    /**
     * Submit statistics and high scores
     * https://docs.kongregate.com/docs/server-api-statistics
     * @param  string $gameApiKey Your private API key
     * @param  int    $userId     The Kongregate user ID for the user to submit for
     * @param  array  $statistics Associative array of statistics (eg. ["Score"=>1000, "Coins"=>45])
     * @return HttpResponse       Http Response
     */
    public static function statistics(string $gameApiKey, int $userId, array $statistics)
    {
      $headers = ["Content-type: application/json"];
      $body = $statistics;
      $body["api_key"] = $gameApiKey;
      $body["user_id"] = $userId;
      return self::curl(self::KONG_API_URL.'api/submit_statistics.json','POST', json_encode($body), [], $headers);
    }

    /**
     * Retrieve information about a Kongregate user
     * The $userId and $userName parameters are optional, but you must include one or the other.
     * https://docs.kongregate.com/docs/server-api-user-info
     * @param  string  $userName Username to retrieve information about
     * @param  int     $userId   User ID of user to retrieve information about
     * @param  integer $page     Page number to retrieve
     * @param  boolean $friends  Indicates whether or not to include the friends list
     * @return HttpResponse      Http Response
     */
    public static function userInformation(string $userName = "", int $userId = null, int $page = 1, bool $friends = false)
    {
      $query = [
        "page_num" => $page,
        "friends" => $friends
      ];
      if($userName !== ""){$query["username"] = $userName;}
      if($userId !== null){$query["user_id"] = $userId;}
      return self::curl(self::KONG_API_URL.'api/user_info.json','GET','',$query);
    }

    /**
     * Retrieve information about Kongregate users
     * The $userIds and $userNames parameters are optional, but you must include one or the other.
     * https://docs.kongregate.com/docs/server-api-user-info
     * @param  array   $userNames Comma-delimited list of users to retrieve information about (max 50)
     * @param  array   $userIds   Comma-delimited list of users IDs to retrieve information about (max 50)
     * @param  integer $page      Page number to retrieve
     * @return HttpResponse       Http Response
     */
    public static function usersInformation(array $userNames = [], array $userIds = [], int $page = 1)
    {
      $query = ["page_num" => $page];
      if(!empty($userNames)){$query["usernames"] = implode(",", $userNames);}
      if(!empty($userIds)){$query["user_ids"] = implode(",", $userIds);}
      return self::curl(self::KONG_API_URL.'api/user_info.json','GET','',$query);
    }

    /**
     * Execute curl and return response.
     * @param  string $url     URL to request
     * @param  string $method  Http method; One of: GET,POST,PUT,DELETE
     * @param  string $body    JSON encoded Data to transfer as the body of a POST or PUT call
     * @param  array  $query   Query Params
     * @param  array $headers  Http Headers
     * @return HttpResponse    Http Response
     */
    private static function curl(string $url, string $method, string $body = "", array $query=[], array $headers = [])
    {
      $METHODS = array("GET","POST","PUT","DELETE");
      if (!in_array($method, $METHODS)) {
          throw new Exception("Method '".$method."' not allowed.");
          return false;
      }
      $ch = curl_init();
      if(!empty($query)){
        $url .= "?".http_build_query($query);
      }
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      if ($method === "POST" || $method === "PUT") {
          curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
      }
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      $message = curl_exec($ch);
      $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
      $response = new HttpResponse($status, $message, $contentType);
      $curlError = curl_error($ch);
      if (!empty($curlError)) {
          $response->setError($curlError);
      }
      curl_close($ch);
      return $response;
    }
}
