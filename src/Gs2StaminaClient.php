<?php
/*
 Copyright Game Server Services, Inc.

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */

namespace GS2\Stamina;

use GS2\Core\Gs2Credentials as Gs2Credentials;
use GS2\Core\AbstractGs2Client as AbstractGs2Client;
use GS2\Core\Exception\NullPointerException as NullPointerException;

/**
 * GS2-Ranking クライアント
 *
 * @author Game Server Services, inc. <contact@gs2.io>
 * @copyright Game Server Services, Inc.
 *
 */
class Gs2StaminaClient extends AbstractGs2Client {

	public static $ENDPOINT = 'stamina';
	
	/**
	 * コンストラクタ
	 * 
	 * @param string $region リージョン名
	 * @param Gs2Credentials $credentials 認証情報
	 * @param $options オプション
	 */
	public function __construct($region, Gs2Credentials $credentials, $options = []) {
		parent::__construct($region, $credentials, $options);
	}
	
	/**
	 * スタミナプールリストを取得
	 * 
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* staminaPoolId => スタミナプールID
	 * 		* ownerId => オーナーID
	 * 		* name => スタミナプール名
	 * 		* description => 説明文
	 * 		* serviceClass => サービスクラス
	 * 		* increaseInterval => スタミナの更新速度
	 * 		* createAt => 作成日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeStaminaPool($pageToken = NULL, $limit = NULL) {
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
					'Gs2Stamina', 
					'DescribeStaminaPool', 
					Gs2StaminaClient::$ENDPOINT, 
					'/staminaPool',
					$query);
	}
	
	/**
	 * スタミナプールを作成<br>
	 * <br>
	 * GS2-Staminaを利用するには、まずスタミナプールを作成する必要があります。<br>
	 * スタミナプールには複数のユーザのスタミナ値を格納することができます。<br>
	 * <br>
	 * スタミナプールの設定として、スタミナ値の回復速度を秒単位で指定できます。<br>
	 * この設定値を利用して、スタミナ値の回復処理を行いつつユーザごとに最新のスタミナ値を取得することができます。<br>
	 * 
	 * @param array $request
	 * * name => スタミナプール名
	 * * description => 説明文
	 * * serviceClass => サービスクラス
	 * * increaseInterval => スタミナの更新速度
	 * @return array
	 * * item
	 * 	* staminaPoolId => スタミナプールID
	 * 	* ownerId => オーナーID
	 * 	* name => スタミナプール名
	 * 	* description => 説明文
	 * 	* serviceClass => サービスクラス
	 * 	* increaseInterval => スタミナの更新速度
	 * 	* createAt => 作成日時
	 */
	public function createStaminaPool($request) {
		if(is_null($request)) throw new NullPointerException();
		$body = [];
		if(array_key_exists('name', $request)) $body['name'] = $request['name'];
		if(array_key_exists('description', $request)) $body['description'] = $request['description'];
		if(array_key_exists('serviceClass', $request)) $body['serviceClass'] = $request['serviceClass'];
		if(array_key_exists('increaseInterval', $request)) $body['increaseInterval'] = $request['increaseInterval'];
		$query = [];
		return $this->doPost(
					'Gs2Stamina', 
					'CreateStaminaPool', 
					Gs2StaminaClient::$ENDPOINT, 
					'/staminaPool',
					$body,
					$query);
	}

	/**
	 * サービスクラスリストを取得
	 *
	 * @return array サービスクラス
	 */
	public function describeServiceClass() {
		$query = [];
		$result = $this->doGet(
				'Gs2Stamina',
				'DescribeServiceClass',
				Gs2StaminaClient::$ENDPOINT,
				'/staminaPool/serviceClass',
				$query);
		return $result['items'];
	}
	
	/**
	 * スタミナプールを取得
	 * 
	 * @param array $request
	 * * staminaPoolName => スタミナプール名
	 * @return array
	 * * item
	 * 	* staminaPoolId => スタミナプールID
	 * 	* ownerId => オーナーID
	 * 	* name => スタミナプール名
	 * 	* description => 説明文
	 * 	* serviceClass => サービスクラス
	 * 	* increaseInterval => スタミナの更新速度
	 * 	* createAt => 作成日時
	 */
	public function getStaminaPool($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('staminaPoolName', $request)) throw new NullPointerException();
		if(is_null($request['staminaPoolName'])) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Stamina',
				'GetStaminaPool',
				Gs2StaminaClient::$ENDPOINT,
				'/staminaPool/'. $request['staminaPoolName'],
				$query);
	}

	/**
	 * スタミナプールの状態を取得
	 *
	 * @param array $request
	 * * staminaPoolName => スタミナプール名
	 * @return array
	 * * status => 状態
	 *
	 */
	public function getStaminaPoolStatus($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('staminaPoolName', $request)) throw new NullPointerException();
		if(is_null($request['staminaPoolName'])) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Stamina',
				'GetStaminaPoolStatus',
				Gs2StaminaClient::$ENDPOINT,
				'/staminaPool/'. $request['staminaPoolName']. '/status',
				$query);
	}
	
	/**
	 * スタミナプールを更新
	 * 
	 * @param array $request
	 * * staminaPoolName => スタミナプール名
	 * * description => 説明文
	 * * serviceClass => サービスクラス
	 * * increaseInterval => スタミナの更新速度
	 * @return array
	 * * item
	 * 	* staminaPoolId => スタミナプールID
	 * 	* ownerId => オーナーID
	 * 	* name => スタミナプール名
	 * 	* description => 説明文
	 * 	* serviceClass => サービスクラス
	 * 	* increaseInterval => スタミナの更新速度
	 * 	* createAt => 作成日時
	 */
	public function updateStaminaPool($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('staminaPoolName', $request)) throw new NullPointerException();
		if(is_null($request['staminaPoolName'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('description', $request)) $body['description'] = $request['description'];
		if(array_key_exists('serviceClass', $request)) $body['serviceClass'] = $request['serviceClass'];
		if(array_key_exists('increaseInterval', $request)) $body['increaseInterval'] = $request['increaseInterval'];
		$query = [];
		return $this->doPut(
				'Gs2Stamina',
				'UpdateStaminaPool',
				Gs2StaminaClient::$ENDPOINT,
				'/staminaPool/'. $request['staminaPoolName'],
				$body,
				$query);
	}
	
	/**
	 * スタミナプールを削除
	 * 
	 * @param array $request
	 * * staminaPoolName => スタミナプール名
	 */
	public function deleteStaminaPool($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('staminaPoolName', $request)) throw new NullPointerException();
		if(is_null($request['staminaPoolName'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
					'Gs2Stamina', 
					'DeleteStaminaPool', 
					Gs2StaminaClient::$ENDPOINT, 
					'/staminaPool/'. $request['staminaPoolName'],
					$query);
	}

	/**
	 * スタミナ値を取得<br>
	 * <br>
	 * 指定したユーザの最新のスタミナ値を取得します。<br>
	 * 回復処理などが行われた状態の値が応答されますので、そのままゲームで利用いただけます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * staminaPoolName => スタミナプール名
	 * * maxValue => スタミナ値の最大値
	 * * accessToken => アクセストークン
	 * @return array
	 * * item
	 * 	* userId => ユーザID
	 * 	* value => スタミナ値
	 * 	* overflow => 最大値を超えているスタミナ値
	 * 	* lastUpdateAt => 更新日時
	 * * nextIncreaseTimestamp => 次回スタミナ値が回復するタイムスタンプ(unixepoch)
	 */
	public function getStamina($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('staminaPoolName', $request)) throw new NullPointerException();
		if(is_null($request['staminaPoolName'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		if(array_key_exists('maxValue', $request)) $query['maxValue'] = $request['maxValue'];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doGet(
				'Gs2Stamina',
				'GetStamina',
				Gs2StaminaClient::$ENDPOINT,
				'/staminaPool/'. $request['staminaPoolName']. '/stamina',
				$query,
				$extparams);
	}

	/**
	 * スタミナ値を増減させる<br>
	 * <br>
	 * 同一ユーザに対するスタミナ値の増減処理が衝突した場合は、後でリクエストを出した側の処理が失敗します。<br>
	 * そのため、同時に複数のデバイスを利用してゲームを遊んでいる際に、一斉にクエストを開始することで1回分のスタミナ消費で2回ゲームが遊べてしまう。<br>
	 * というような不正行為を防ぐことが出来るようになっています。<br>
	 * <br>
	 * クエストに失敗した時に消費したスタミナ値を戻してあげる際や、スタミナ値の回復アイテムを利用した際などに<br>
	 * スタミナ値を増やす操作を行うことになりますが、その際に overflow に true を指定することで、スタミナ値の最大値を超える回復を行えます。<br>
	 * スタミナ値の上限を超えた部分は overflow フィールドに格納され、優先してそちらが消費されます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * staminaPoolName => スタミナプール名
	 * * variation => スタミナ値の増減量
	 * * maxValue => スタミナ値の最大値
	 * * overflow => スタミナ値の最大値を超えることを許容するか
	 * * accessToken => アクセストークン
	 * @return array
	 * * item
	 * 	* userId => ユーザID
	 * 	* value => スタミナ値
	 * 	* overflow => 最大値を超えているスタミナ値
	 * 	* lastUpdateAt => 更新日時
	 */
	public function changeStamina($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('staminaPoolName', $request)) throw new NullPointerException();
		if(is_null($request['staminaPoolName'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('variation', $request)) $body['variation'] = $request['variation'];
		if(array_key_exists('maxValue', $request)) $body['maxValue'] = $request['maxValue'];
		if(array_key_exists('overflow', $request)) $body['overflow'] = $request['overflow'];
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doPost(
				'Gs2Stamina', 
				'ChangeStamina', 
				Gs2StaminaClient::$ENDPOINT, 
				'/staminaPool/'. $request['staminaPoolName']. '/stamina',
				$body,
				$query,
				$extparams);
	}
	
}