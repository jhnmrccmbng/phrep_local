<?php
	// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function login_ok($memberInfo, &$args){
            if($memberInfo['group']=='Secretary'){
                return 'main/sec_dashboard_active.php';
            }
            else if($memberInfo['group']=='Researcher'){
                return 'main/dashboard.php';
            }
            else if($memberInfo['group']=='Reviewer'){
                return 'main/rev_dashboard.php';
            }
            else if($memberInfo['group']=='Coordinator'){
                return 'main/co_dashboard.php';
            }
            else
                return '';
	}

	function login_failed($attempt, &$args){

	}

	function member_activity($memberInfo, $activity, &$args){
		switch($activity){
			case 'pending':
				break;

			case 'automatic':
				break;

			case 'profile':
				break;

			case 'password':
				break;

		}
	}

	function sendmail_handler(&$pm){

	}
