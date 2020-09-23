<?php
if(!defined('__XE__')) exit();
/**
* @file no_modify_comment.addon.php
* @author 불금 (nettermhell@gmail.com)
* @brief 대댓글이 정해진 개수 이상일 경우, 수정을 못하게 막습니다.
**/

if($called_position == 'before_module_proc' && $this->act == 'dispBoardModifyComment'){
	$logged_info = Context::get('logged_info');
	if($addon_info->admin_allow == 'Y' && $logged_info->is_admin == 'Y') return; //관리자이고 수정가능이면 리턴
	
	
	$comment_srl = Context::get('comment_srl');
	$oCommentModel = getModel('comment');
	$ChildCommentCount = $oCommentModel->getChildCommentCount($comment_srl);
	
	$ChildCommentLimit = $addon_info->ChildCommentLimit;
	if(!$ChildCommentLimit) $ChildCommentLimit = 2; //default value
	
	if($ChildCommentCount >= $ChildCommentLimit) {
		
		$message = $addon_info->res_message;
		$message = str_replace("@num", $ChildCommentLimit, $message); //@num 치환
		
		if(!$message) $message = sprintf('대댓글이 %s개 이상 달린 댓글은 수정이 불가합니다.\n(필요시 관리자 호출)', $ChildCommentLimit);
		
		$script =  sprintf('<script type="text/javascript"> { history.back(); alert("%s");  }</script>', $message);
		Context::addHtmlHeader($script);
		
		return $this->stop(); //Security -> note : if no use JavaScript
	}
}
?>