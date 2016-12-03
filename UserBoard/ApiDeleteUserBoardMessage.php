<?php

class ApiDeleteUserBoardMessage extends ApiBase {
	public function execute() {
		$main = $this->getMain();
		$user = $this->getUser();

		$messageId = $main->getVal( 'id' );

		// Don't allow deleting messages when the database is locked for some reason
		if ( wfReadOnly() ) {
			$this->getResult()->addValue( null, 'result', 'You cannot delete messages right now.' );
			return true;
		}

		$b = new UserBoard();
		if (
			$b->doesUserOwnMessage( $user->getId(), $messageId ) ||
			$user->isAllowed( 'userboard-delete' )
		)
		{
			$b->deleteMessage( $messageId );
		}

		$this->getResult()->addValue( null, 'result', 'ok' );

		return true;
	}

	public function getDescription() {
		return 'Delete a UserBoard message.';
	}

	public function getAllowedParams() {
		return array_merge( parent::getAllowedParams(), array(
			'id' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => false
			)
		) );
	}

	public function getParamDescription() {
		return array_merge( parent::getParamDescription(), array(
			'id' => 'Unique identifier of the board message to delete.'
		) );
	}

	public function getExamplesMessages() {
		return array();
	}
}