<?php

class WufooHTMLWrapper {


	/**
	 * Define your Wufoo API key and the subdomain of your wufoo account
	 */
	const WUFOO_API_KEY = 'INSERT API KEY';
	const WUFOO_SUBDOMAIN = 'INSERT SUBDOMAIN';


	/**
	 * The common named used to prefix all input name attributes
	 */
	private $prefix;


	/**
	 * An array of all errors caused on entering the data
	 * @var Array
	 */
	private $errors;


	/**
	 * An option to "turn on" Bootstrap styles
	 * @var Boolean
	 */
	private $use_bootstrap;


	/**
	 * Start the class
	 */
	public function __construct() {

		$this->errors = array();
		$this->use_bootstrap = false;
		$this->prefix = 'wufoo_forms';

	}


	/**
	 * Quick function to turn on Bootstrap classes
	 * @return Boolean Whether it's enabled or not
	 */
	public function enableBootstrap() {

		$this->use_bootstrap = true;

		return $this->use_bootstrap;

	}


	/**
	 * The main function to build the form HTML from the API
	 * @param  String  $form_id   	The form ID OR the form slug - i.e. 'z172ip8e07gen9n' OR 'my-form'
	 * @return String  $form_html	The final HTML of hte complete form
	 */
	public function buildForm($form_id, $show_title = false, $show_description = false) {

		$fields = $this->getFields($form_id);
		$form_data = $this->getForm($form_id);

		if( empty($fields) || !$fields ) return false;

		$form_html = '<h1>'.$form_data->Name.'</h1>';

		$form_html .= '<p>'.$form_data->Description.'</p>';

		$form_html .= '<form class="wufoo_form" method="post" action="" id="'.$form_id.'" onsubmit="validateFields()">';

			$ignoreFields = array('EntryId', 'DateCreated', 'CreatedBy', 'UpdatedBy', 'LastUpdated');

			$form_html .= $this->getErrors($form_id);

			foreach($fields as $field) {

				if( in_array($field->ID, $ignoreFields) ) continue;

				$form_html .= $this->getFieldHTML($field->Type, $field, $form_id);

			}

			$form_html .= '<button type="submit" class="btn btn-primary">Save Data</button>';

		$form_html .= '</form>';

		return $form_html;

	}


	/**
	 * Function to filter out the URL to get the ID
	 * @param  String $input  	The initial input URL or ID
	 * @return String $output 	The final ID for the API
	 */
	private function getFormID($input) {

		$output = $input;

		return $output;

	}


	/**
	 * HTML builder function for the different field types. This is pretty ugly but it gets the job done. Please look at it in chunks
	 * @param  String $type    Form type - i.e. text, select, textarea, etc
	 * @param  Object $field   Field data from Wufoo
	 * @param  String $form_id ID of the form
	 * @return String $html    Full final HTML created for this field
	 */
	private function getFieldHTML($type, $field, $form_id) {

		$html = '<div class="wufoo_field' . ( $this->use_bootstrap ? ' form-group' : '' ) . '" id="'.$field->ID.'_wrapper">';

			$html .= '<label>' . $field->Title . $this->is_required_notice($field) . '</label>';

			if( $field->Instructions ) $html .= '<p class="description"><em>'.$field->Instructions.'</em></p>';

			switch ($field->Type) {

				case 'checkbox':

					if( !$field->SubFields ) return false;

					if( $this->use_bootstrap ) $html .= '<div class="clearfix"></div>';

					$f = 1;

					foreach($field->SubFields as $subfield) {

						$html .= '<div class="checkbox' . ($this->use_bootstrap ? ' col-sm-6' : '') . '">';

							$html .= '<label>';

								$html .= '<input id="'.$subfield->ID.'" name="'.$this->prefix.'['.$form_id.']['.$subfield->ID.']" value="'.$subfield->Label.'" type="'.$field->Type.'" '. ($field->IsRequired ? 'required' : '') . ( $subfield->DefaultVal === '1' ? ' checked' : '' ) . '/>' . $subfield->Label;

							$html .= '</label>';

						$html .= '</div>';
						
						if( $this->use_bootstrap && $f % 2 == 0 ) $html .= '<div class="clearfix"></div>';

						$f++;

					}

					if( $this->use_bootstrap ) $html .= '<div class="clearfix"></div>';

					break;



				case 'select':

					if( !$field->Choices ) return false;

					$html .= '<select class="' . ( $this->use_bootstrap ? 'form-control' : '' ) . '" id="'.$field->ID.'" name="'.$this->prefix.'['.$form_id.']['.$field->ID.']" '. ($field->IsRequired ? 'required' : '') .'>';

						foreach($field->Choices as $choice) {

							$html .= '<option>' . $choice->Label . '</option>';
						
						}

					$html .= '</select>';

					break;



				case 'radio':

					if( !$field->Choices ) return false;

					foreach($field->Choices as $choice) {

						$html .= '<div class="radio">';

							$html .= '<label>';

								$html .= '<input id="'.$field->ID.'" name="'.$this->prefix.'['.$form_id.']['.$field->ID.']" value="'.$choice->Label.'" type="'.$field->Type.'" '. ($field->IsRequired ? 'required' : '') . '/>' . $choice->Label;

							$html .= '</label>';

						$html .= '</div>';
					
					}

					break;



				case 'textarea':

					$html .= '<textarea class="' . ( $this->use_bootstrap ? 'form-control' : '' ) . '" id="'.$field->ID.'" name="'.$this->prefix.'['.$form_id.']['.$field->ID.']" '. ($field->IsRequired ? 'required' : '') .'>'. ( $field->DefaultVal ? $field->DefaultVal : '' ) .'</textarea>';
					
					break;



				case 'number':

					$html .= '<input class="' . ( $this->use_bootstrap ? 'form-control' : '' ) . '" id="'.$field->ID.'" name="'.$this->prefix.'['.$form_id.']['.$field->ID.']" type="number" '.(strpos($field->ClassNames, 'percentage') !== false ? 'min="0" max="100" ' : ' ') . ($field->IsRequired ? 'required' : '') . ( $field->DefaultVal ? ' value="'.$field->DefaultVal.'"' : '' ) .'/>';
					
					break;



				default:

					$html .= '<input class="' . ( $this->use_bootstrap ? 'form-control' : '' ) . '" id="'.$field->ID.'" name="'.$this->prefix.'['.$form_id.']['.$field->ID.']" type="'.$field->Type.'" '. ($field->IsRequired ? 'required' : '') . ( $field->DefaultVal ? ' value="'.$field->DefaultVal.'"' : '' ) .'/>';
					
					break;


			};

			if( $this->use_bootstrap ) $html .= '<div class="clearfix"></div>';

		$html .= '</div>';

		return $html;

	}


	/**
	 * Return all the fields for this form
	 * @param  String $form_id 	The Wufoo Form ID
	 * @return Object $fields 	Final field object
	 */
	private function getFields($form_id) {

		$url = 'https://' . self::WUFOO_SUBDOMAIN . '.wufoo.com/api/v3/forms/' . $form_id . '/fields.json';

		$response = $this->_get( $url );
		
		$response = json_decode($response);

		$fields = $response->Fields;

		return $fields;

	}


	/**
	 * Return the form settings and data
	 * @param  String $form_id 	The Wufoo Form ID
	 * @return Object $form 	The final form object with all its data
	 */
	private function getForm($form_id) {

		$url = 'https://' . self::WUFOO_SUBDOMAIN . '.wufoo.com/api/v3/forms/' . $form_id . '.json';

		$response = $this->_get( $url );
		
		$response = json_decode($response);

		$form = $response->Forms[0];

		return $form;

	}


	/**
	 * Simple function to add a Required message for each required field
	 * @param  Object  $field 		The field object
	 * @return String  $message 	The message text
	 */
	private function is_required_notice($field) {

		if(isset($field->IsRequired) && $field->IsRequired === '1') {

			$message = ' <strong>*</strong>';

		}

		return isset($message) ? $message : '';

	}


	/**
	 * Function for submitting the form values
	 * @param  Array 	$data     All the data that's been passed back here
	 * @return Array 			  The final entry IDs in an array by form ID
	 */
	public function sendSubmission() {

		$post_data = isset($_POST[$this->prefix]) ? $_POST[$this->prefix] : false;

		if( !$post_data || empty($post_data) ) return false;

		$return = array();

		foreach ($post_data as $form_id => $form_data) {

			if( empty($form_data) ) continue;

			$url = 'https://' . self::WUFOO_SUBDOMAIN . '.wufoo.com/api/v3/forms/'.$form_id.'/entries.json';

			$response = $this->_post( $url, $form_data );

			$response = json_decode($response);

			if( !isset($response->Success) || $response->Success === 0 ) {

				foreach ($response->FieldErrors as $error_object) {

					$this->addError($form_id, $error_object->ErrorText);

				}

			} else {

				$return[$form_id] = $response->EntryId;

			}

		}

		return $return;

	}


	/**
	 * Add errors to the final error array
	 * @param String $form_id The form the error is associated with
	 * @param String $message The error message to be added
	 */
	private function addError($form_id, $message) {

		if( !isset($this->errors[$form_id]) ) $this->errors[$form_id] = array();

		$this->errors[$form_id][] = $this->use_bootstrap ? '<div class="alert alert-danger">' . $message . '</div>' : '<p style="color:red;"><strong>' . $message . '</strong></p>';

	}


	/**
	 * Return errors to be echoes to the page
	 * @return String $error_string  All the errors in a final string
	 */
	private function getErrors($form_id) {

		$error_string = isset($this->errors[$form_id]) ? implode("", $this->errors[$form_id]) : '';

		return $error_string;

	}
	

	/**
	 * Function to GET data from Wufoo
	 * @param  String $url The url to request from the API call
	 * @return Object      The final data returned from the API call
	 */
	private function _get( $url ) {

		$curl = curl_init($url); 

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Wufoo HTML Wrapper');
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		
		curl_setopt($curl, CURLOPT_USERPWD, self::WUFOO_API_KEY.':footastical');

		$response = curl_exec( $curl );

		curl_close($curl);

		return $response;
	}
	

	/**
	 * Function to POST data to Wufoo
	 * @param  String $url 			The url to request from the API call
	 * @param  Array  $postParams 	The parameters to be passed in the POST request
	 * @return Object      			The final data returned from the API call
	 */
	private function _post( $url, $postParams ) {

		$curl = curl_init($url); 

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Wufoo HTML Wrapper');
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data', 'Expect:'));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postParams);
		
		curl_setopt($curl, CURLOPT_USERPWD, self::WUFOO_API_KEY.':footastical');

		$response = curl_exec( $curl );

		curl_close($curl);

		return $response;

	}
	

}
?>