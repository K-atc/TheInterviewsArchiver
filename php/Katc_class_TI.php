<?php

	include_once("simple_html_dom.php");

	class Katc_TI {

		static $dom = NULL;
		static $user = "";
		static $Q = array();
		static $A = array();
		static $count_max = 0;

		public function get_filter($str) {
			//URL処理
			$str = preg_replace("~(http://theinterviews.jp/|/page/[0-9]{0,})~", "", $str);
			// $str = preg_replace("/(¥/page¥/[0-9]{0,})/", "", $str);
			//ユーザー名処理
			$str = strtolower($str);
			$cut = strpos($str, " ");
			// echo $cut;
			if($cut>0) {
				$str = substr($str, 0, $cut);
			}
			$str = htmlspecialchars($str, ENT_QUOTES);
			return $str;
		}

		public function get_dom($target_user, $page){
			$target_url = "http://theinterviews.jp/$target_user/page/";
			self::$user = $target_user;
			self::$dom = file_get_html($target_url. $page);
			self::get_QA();
		}


		private function get_QA() {

			$i = 0;

			foreach(self::$dom->find('div.title') as $element){
				self::$Q[$i] = $element->plaintext;
				$i++;
			}

			self::$count_max = $i;

			$i = 0;

			foreach(self::$dom->find('div.note') as $element){
				self::$A[$i] = $element->innertext;
				self::$A[$i] = str_replace('href="/' . self::$user .'/img/', 'target="_blank" href="http://theinterviews.jp/' . self::$user . '/img/', self::$A[$i]);
				$i++;
			}
		}


		public function get_finalPage() {

			foreach(self::$dom->find('p.stats') as $element){
				 // echo preg_replace("/(回答率: )[0-9.]{4,}(%   回答済: )/", "", $element->innertext . "");
				$str = $element->innertext;
				// $str = mb_str_replace("\255", "", $str);
				$str = preg_replace("/\s/", "", $str);
				$str = preg_replace("/(回答率:[0-9.]{3,}%)/", "", $str);
				$str = preg_replace("/(回答済:)/", "", $str);
				$str = substr($str, 1, strlen($str)-1); //&nbsp;
				$str = str_replace("nbsp;", "", $str);
				return ceil((int)$str/5);
				 // return preg_replace("/(Answer rate: )[0-9.]{4,}(%   Published: )/", "", $element->innertext . "");
			}
		}


		public function print_QA(){

			for($i=0; $i<self::$count_max; $i++) {
				echo '<div class="units">';
				echo '<div class="questions">' . self::$Q[$i] . '</div>';
				echo '<div class="answers">' . self::$A[$i] . '</div>';
				echo '</div>';
			}
		}


		public function welcome(){

			echo '
				<form class="pure-form" method="get" action="./index.php">
					<fieldset>
					<h2>Select An User To Be Archived</h2>
					<p>アーカイブしたいユーザー名（スクリーンネーム）を入力してください。<br />または，そのユーザーのページのURLを貼り付けてください。</p>
					<div class="panel">
						<dl>
							<dt>対応入力形式の例</dt>
							<dd>redjuice</dd>
							<dd>http://theinterviews.jp/redjuice/</dd>
							<dd>http://theinterviews.jp/redjuice/page/3</dd>
						</dl>
					</div>
					<div class="pure-control-group">
						<input type="text" name="t" class="pure-input-1" placeholder="User Name or URL">
			        </div>
					<div class="pure-control-group submit_center">
						<button type="submit" class="pure-button pure-button-primary">Archive!</button>
			        </div>
					</fieldset>
				</form>
				<h2>Share</h2>
				<p style="position:relative; left:4%; display:block; text-align:center;">
				<a href="https://twitter.com/share" class="twitter-share-button" data-text="TheInterviewsをアーカイブ！(The Interviews Archiver)" data-lang="ja" data-size="large">ツイート</a>
				</p>
				<h2>Any Problems?</h2>
				<p>Please contact me at <a href="https://twitter.com/K_atc" target="_blank">@K_atc (Twitter)</a></p>
			';
		}
	}

?>