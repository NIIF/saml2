<?php

require_once('../../_include.php');


require_once('SimpleSAML/Utilities.php');
require_once('SimpleSAML/Session.php');
require_once('SimpleSAML/Metadata/MetaDataStorageHandler.php');
require_once('SimpleSAML/XML/Shib13/AuthnRequest.php');
require_once('SimpleSAML/Bindings/Shib13/HTTPPost.php');
require_once('SimpleSAML/XHTML/Template.php');

try {
	
	$config = SimpleSAML_Configuration::getInstance();
	$metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();
	
	$binding = new SimpleSAML_Bindings_Shib13_HTTPPost($config, $metadata);
	$authnResponse = $binding->decodeResponse($_POST);

	$authnResponse->validate();
	$session = $authnResponse->createSession(true);

	if (isset($session)) {
		$relayState = $authnResponse->getRelayState();
		if (isset($relayState)) {
			SimpleSAML_Utilities::redirect($relayState);
		} else {
			SimpleSAML_Utilities::fatalError($session->getTrackID(), 'NORELAYSTATE');
		}
	} else {
		SimpleSAML_Utilities::fatalError($session->getTrackID(), 'NOSESSION');
	}


} catch(Exception $exception) {
	SimpleSAML_Utilities::fatalError($session->getTrackID(), 'GENERATEAUTHNRESPONSE', $exception);
}


?>