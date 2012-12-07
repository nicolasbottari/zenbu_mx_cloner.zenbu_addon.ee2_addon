<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Zenbu MX Cloner support extension
 * =========================================
 * Enables display of a column for the MX cloner add-on
 * @version 	1.0.0 
 * @author 		Nicolas Bottari - Zenbu Studio
 * ------------------------------ 
 * 
 * *** IMPORTANT NOTES ***
 * I (Nicolas Bottari/Zenbu Studio) am not responsible for any
 * damage, data loss, etc caused directly or indirectly by the use of this extension. 
 *
 * REQUIRES Zenbu module:
 * @link	http://zenbustudio.com/software/zenbu/
 * @see		http://zenbustudio.com/software/docs/zenbu/
 *
 * MX Cloner add-on: 
 * @author 	Max Lazar
 * @link 	http://devot-ee.com/add-ons/mx-cloner
 * @link 	http://www.eec.ms/add-ons/mx-cloner
 * 
 */
class Zenbu_mx_cloner_ext {
	
	var $name				= 'Zenbu MX Cloner support extension';
	var $addon_short_name 	= 'zenbu_mx_cloner';
	var $version 			= '1.0.0'; 
	var $description		= 'Enables display of a column for the MX cloner add-on';
	var $settings_exist		= 'n';
	var $docs_url			= 'http://nicolasbottari.com/expressionengine_cms/docs/zenbu-mx-cloner';
	var $settings        	= array();

	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	function __construct($settings='')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
		$this->EE->lang->loadfile('zenbu_mx_cloner');
	}
	
	/**
	 * ===============================
	 * function zenbu_mx_cloner_array
	 * ===============================
	 * Adds a row in Zenbu's Display settings section
	 * @return array 	$output 	An array of data used by Zenbu
	 * The $output array must have the following keys:
	 * column: Computer-readable used as identifier for settings. Keep it unique!
	 * label: Human-readable label used in the Display settings row.
	 */
	function zenbu_mx_cloner_array()
	{
		// Get whatever was passed through this hook from previous add-ons
		$field = $this->EE->extensions->last_call;

		// Add to this array with this add-on's data
		$field[] = array(
			'column'	=> 'show_mx_cloner',						// Computer/Cylon-readable
			'label'		=> $this->EE->lang->line('show_mx_cloner'),	// Human-readable
			);
		return $field;
	}

	/**
	 * ======================================
	 * function zenbu_mx_cloner_cell_content
	 * ======================================
	 * Adds data to a Zenbu entry row
	 * @param int 	$entry_id 		The current Entry ID
	 * @param array $entry_array 	An array of all entries found by Zenbu
	 * @param int 	$channel_id 	The current channel ID for the entry
	 * 
	 * @return array 	$output 	An array of data used by Zenbu. 
	 * The key must match the computer-readable identifier, minus the 'show_' part.
	 */
	function zenbu_mx_cloner_cell_content($entry_id, $entry_array, $channel_id)
	{
		// Build filter for entry title link
		$filter_array						= array();
		$filter_array["return_to_zenbu"]	= "y";
		$filter_array						= base64_encode(serialize($filter_array));

		// Get whatever was passed through this hook from previous add-ons
		$output = $this->EE->extensions->last_call;

		// Add to this array with this add-on's data
		$output['mx_cloner'] = anchor(BASE.AMP."C=content_publish".AMP."M=entry_form".AMP."channel_id=".$channel_id.AMP."entry_id=".$entry_id.AMP."clone=y&use_autosave=n".AMP."filter=".$filter_array, $this->EE->lang->line("clone"));	

		return $output;
	}

	/**
	 * ===========================================
	 * function zenbu_mx_cloner_custom_order_sort
	 * ===========================================
	 * Adds custom entry ordering/sorting
	 * Build on top of main Active Record to retrieve Zenbu results
	 * @param string 	$sort 		The sort order (asc/desc)
	 * 
	 * @return void 
	 */
	function zenbu_mx_cloner_custom_order_sort($sort)
	{
		// Cells are pretty much the same, so nothing important to sort.
	}
	
	
	/**
	 * Settings Form
	 *
	 * @param	Array	Settings
	 * @return 	void
	 */
	function settings_form()
	{	
		return "";			
	}
	
	/**
	* Save Settings
	*
	* This function provides a little extra processing and validation 
	* than the generic settings form.
	*
	* @return void
	*/
	function save_settings()
	{
		if (empty($_POST))
		{
			show_error($this->EE->lang->line('unauthorized_access'));
		}
		
		unset($_POST['submit']);
		
		$settings = $_POST;
		
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->update('extensions', array('settings' => serialize($settings)));
		
		$this->EE->session->set_flashdata(
			'message_success',
		 	$this->EE->lang->line('preferences_updated')
		);
	}



	function activate_extension() {
	
			$data[] = array(
			    'class'      => __CLASS__,
			    'method'    => "zenbu_mx_cloner_array",
			    'hook'      => "zenbu_add_column",
			    'settings'    => serialize(array()),
			    'priority'    => 110,
			    'version'    => $this->version,
			    'enabled'    => "y"
			  );

			$data[] = array(
			    'class'      => __CLASS__,
			    'method'    => "zenbu_mx_cloner_cell_content",
			    'hook'      => "zenbu_entry_cell_data",
			    'settings'    => serialize(array()),
			    'priority'    => 110,
			    'version'    => $this->version,
			    'enabled'    => "y"
			  );

	    	$data[] = array(
		        'class'      => __CLASS__,
		        'method'    => "zenbu_mx_cloner_custom_order_sort",
		        'hook'      => "zenbu_custom_order_sort",
		        'settings'    => serialize(array()),
		        'priority'    => 110,
		        'version'    => $this->version,
		        'enabled'    => "y"
		      );
		      
		 
		      	
	      // insert in database
	      foreach($data as $key => $data) {
	      $this->EE->db->insert('exp_extensions', $data);
	      }
	  }
	
	
	function disable_extension() {

	  $this->EE->db->where('class', __CLASS__);
	  $this->EE->db->delete('exp_extensions');

	} 
	  
	  /**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
		
		if ($current < $this->version)
		{
			// Update to version 1.0
		}
		
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->update(
					'extensions', 
					array('version' => $this->version)
		);
	}
  

}
// END CLASS