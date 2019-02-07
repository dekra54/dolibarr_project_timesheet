<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 *
 * This program is free software;you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation;either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY;without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * 	\defgroup   mymodule     Module MyModule
 *  \brief      Example of a module descriptor.
 *				Such a file must be copied into htdocs/mymodule/core/modules directory.
 *  \file       htdocs/mymodule/core/modules/modTimesheet.class.php
 *  \ingroup    mymodule
 *  \brief      Description and activation file for module MyModule
 */
include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';
/**
 *  Description and activation class for module MyModule
 */
class modTimesheet extends DolibarrModules
{
	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
        global $langs, $conf;
        $this->db = $db;
		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 861002;
		// Key text used to identify module (for permissions, menus, etc...)
		$this->rights_class = 'timesheet';
		// Family can be 'crm', 'financial', 'hr', 'projects', 'products', 'ecm', 'technic', 'other'
		// It is used to group modules in module setup page
		$this->family = "projects";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i', '', get_class($this));
		// Module description, used if translation string 'ModuleXXXDesc' not found (where XXX is value of numeric property 'numero' of module)
		$this->description = "TimesheetView";
		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = '4.0.0';
		// Key used in llx_const table to save module status enabled/disabled (where MYMODULE is value of property name of module in uppercase)
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		// Where to store the module in setup page (0=common, 1=interface, 2=others, 3=very specific)
		$this->special = 0;
		// Name of image file used for this module.
		// If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
		// If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
		$this->picto='timesheet@timesheet';
		// Defined all module parts (triggers, login, substitutions, menus, css, etc...)
		// for default path (eg: /mymodule/core/xxxxx) (0=disable, 1=enable)
		// for specific path of parts (eg: /mymodule/core/modules/barcode)
		// for specific css file (eg: /mymodule/css/mymodule.css.php)
		$this->module_parts = array('triggers' => 0,
                                            'css' => array('/timesheet/core/css/timesheet.css'));
                ////$this->module_parts = array(
		//                        	'triggers' => 0,                               	// Set this to 1 if module has its own trigger directory (core/triggers)
		//							'login' => 0,                                  	// Set this to 1 if module has its own login method directory (core/login)
		//							'substitutions' => 0,                          	// Set this to 1 if module has its own substitution function file (core/substitutions)
		//							'menus' => 0,                                  	// Set this to 1 if module has its own menus handler directory (core/menus)
		//							'theme' => 0,                                  	// Set this to 1 if module has its own theme directory (theme)
		//                        	'tpl' => 0,                                    	// Set this to 1 if module overwrite template dir (core/tpl)
		//							'barcode' => 0,                                	// Set this to 1 if module has its own barcode directory (core/modules/barcode)
		//							'models' => 0,                                 	// Set this to 1 if module has its own models directory (core/modules/xxx)
		//							'css' => array('/mymodule/css/mymodule.css.php'), 	// Set this to relative path of css file if module has its own css file
	 	//							'js' => array('/mymodule/js/mymodule.js'),        // Set this to relative path of js file if module must load a js on all pages
		//							'hooks' => array('hookcontext1', 'hookcontext2')  	// Set here all hooks context managed by module
		//							'dir' => array('output' => 'othermodulename'),    // To force the default directories names
		//							'workflow' => array('WORKFLOW_MODULE1_YOURACTIONTYPE_MODULE2'=>array('enabled'=>'! empty($conf->module1->enabled) && ! empty($conf->module2->enabled)', 'picto'=>'yourpicto@mymodule')) // Set here all workflow context managed by module
		//                        );
		//$this->module_parts = array();
                //$this->module_parts = array('css' => array('/timesheet/css/timesheet.css'));
		// Data directories to create when module is enabled.
		// Example: this->dirs = array("/mymodule/temp");
		$this->dirs = array("/timesheet", "/timesheet/reports", "/timesheet/users", "/timesheet/tasks");
		// Config pages. Put here list of php page, stored into mymodule/admin directory, to use to setup module.
		$this->config_page_url = array("timesheetsetup.php@timesheet");
		// Dependencies
		$this->hidden = false;			// A condition to hide module
		$this->depends = array('modProjet');		// List of modules id that must be enabled if this module is enabled
		$this->requiredby = array();	// List of modules id to disable if this one is disabled
		$this->conflictwith = array();	// List of modules id this module is in conflict with
		$this->phpmin = array(5, 0);					// Minimum version of PHP required by module
		$this->need_dolibarr_version = array(3, 5);	// Minimum version of Dolibarr required by module
		$this->langfiles = array("timesheet@timesheet");
		// Constants
		// List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
		// Example: $this->const=array(0=>array('MYMODULE_MYNEWCONST1', 'chaine', 'myvalue', 'This is a constant to add', 1),
		//                             1=>array('MYMODULE_MYNEWCONST2', 'chaine', 'myvalue', 'This is another constant to add', 0, 'current', 1)
		// );
                $r=0;
		$this->const = array();
                //$this->const[$r] = array("TIMESHEET_ATTENDANCE", "int", 1, "layout mode of the timesheets");// hours or days
                //$r++;
                $this->const[$r] = array("TIMESHEET_TIME_TYPE", "chaine", "hours", "layout mode of the timesheets");// hours or days
                $r++;
                $this->const[$r] = array("TIMESHEET_DAY_DURATION", "int", 8, "number of hour per day (used for the layout per day)");
                $r++;
                $this->const[$r] = array("TIMESHEET_HIDE_DRAFT", "int", 0, "option to mask to task belonging to draft project");
                $r++;
                $this->const[$r] = array("TIMESHEET_HIDE_ZEROS", "int", 0, "option to hide the 00:00");
                $r++;
                $this->const[$r] = array("TIMESHEET_HEADERS", "chaine", "Tasks", "list of headers to show inthe timesheets");
                $r++;
                $this->const[$r] = array("TIMESHEET_HIDE_REF", "int", 0, "option to hide the ref in the timesheets");
                $r++;
                $this->const[$r] = array("TIMESHEET_WHITELIST_MODE", "int", 0, "Option to change the behaviour of the whitelist:-whiteliste, 1-blackliste, 2-no impact ");
                $r++;
                $this->const[$r] = array("TIMESHEET_WHITELIST", "int", 1, "Activate the whitelist:");
                $r++;
                $this->const[$r] = array("TIMESHEET_COL_DRAFT", "chaine", "FFFFFF", "color of draft");
                $r++;
                $this->const[$r] = array("TIMESHEET_COL_SUBMITTED", "chaine", "00FFFF", "color of submitted");
                $r++;
                $this->const[$r] = array("TIMESHEET_COL_APPROVED", "chaine", "00FF00", "color of approved");
                $r++;
                $this->const[$r] = array("TIMESHEET_COL_CANCELLED", "chaine", "FFFF00", "color of cancelled");
                $r++;
                $this->const[$r] = array("TIMESHEET_COL_REJECTED", "chaine", "FF0000", "color of rejected");
                $r++;
                $this->const[$r] = array("TIMESHEET_DAY_MAX_DURATION", "int", 12, "max working hours per days");
                $r++;
                $this->const[$r] = array("TIMESHEET_ADD_HOLIDAY_TIME", "int", 1, "count the holiday in total or not");
                $r++;
                $this->const[$r] = array("TIMESHEET_OPEN_DAYS", "chaine", "_1111100", "normal day for time booking");
                $r++;
                $this->const[$r] = array("TIMESHEET_APPROVAL_BY_WEEK", "int", 0, "Approval by week instead of by user");
                $r++;
                $this->const[$r] = array("TIMESHEET_MAX_APPROVAL", "int", 5, "Max TS per Approval page");
                $r++;
                $this->const[$r] = array("TIMESHEET_ADD_DOCS", "int", 0, "Allow to join files to timesheets");
                $r++;
                $this->const[$r] = array("TIMESHEET_APPROVAL_FLOWS", "chaine", "_00000", "Approval flows ");
                $r++;
                 $this->const[$r] = array("TIMESHEET_INVOICE_METHOD", "int", 0, "Approval by week instead of by user");
                $r++;
                $this->const[$r] = array("TIMESHEET_INVOICE_TASKTIME", "chaine", "all", "set the default task to include in the invoice item");
                $r++;
                $this->const[$r] = array("TIMESHEET_INVOICE_TIMETYPE", "chaine", "days", "set the default task to include in the invoice item");
                $r++;
                $this->const[$r] = array("TIMESHEET_INVOICE_SERVICE", "int", 0, "set a default service for the invoice item");
                $r++;
                $this->const[$r] = array("TIMESHEET_INVOICE_SHOW_TASK", "int", 1, "Show task on the invoice item ");
                $r++;
                $this->const[$r] = array("TIMESHEET_INVOICE_SHOW_USER", "int", 1, "Show user on the invoice item ");
                $r++;
                $this->const[$r] = array("TIMESHEET_TIME_SPAN", "chaine", "splitedWeek", "timespan of the timesheets");// hours or days
                $r++;
                $this->const[$r] = array("TIMESHEET_ADD_FOR_OTHER", "int", 0, "enable to time spent entry for subordinates");// hours or days
                $r++;
                $this->const[$r] = array("TIMESHEET_VERSION", "chaine", $this->version, "save the timesheet verison");// hours or days
                $r++;
                $this->const[$r] = array("TIMESHEET_SHOW_TIMESPENT_NOTE", "int", 1, "show the note next to the time entry");// hours or days
                $r++;
                $this->const[$r] = array("TIMESHEET_PDF_NOTEISOTASK", "int", 0, "save the timesheet verison");// hours or days
                $r++;
                $this->const[$r] = array("TIMESHEET_EVENT_MAX_DURATION", "int", 0, "max evebt duration");// hours or days
                $r++;
                $this->const[$r] = array("TIMESHEET_EVENT_DEFAULT_DURATION", "int", 0, "max evebt duration");// hours or days
                $r++;
                //$this->const[2] = array("CONST3", "chaine", "valeur3", "Libelle3");
		// Array to add new pages in new tabs
		// Example: $this->tabs = array('objecttype:+tabname1:Title1:mylangfile@mymodule:$user->rights->mymodule->read:/mymodule/mynewtab1.php?id=__ID__', 	// To add a new tab identified by code tabname1
        //                              'objecttype:+tabname2:Title2:mylangfile@mymodule:$user->rights->othermodule->read:/mymodule/mynewtab2.php?id=__ID__', 	// To add another new tab identified by code tabname2
        //                              'objecttype:-tabname':NU:conditiontoremove);// To remove an existing tab identified by code tabname
		// where objecttype can be
		// 'thirdparty'       to add a tab in third party view
		// 'intervention'     to add a tab in intervention view
		// 'order_supplier'   to add a tab in supplier order view
		// 'invoice_supplier' to add a tab in supplier invoice view
		// 'invoice'          to add a tab in customer invoice view
		// 'order'            to add a tab in customer order view
		// 'product'          to add a tab in product view
		// 'stock'            to add a tab in stock view
		// 'propal'           to add a tab in propal view
		// 'member'           to add a tab in fundation member view
		// 'contract'         to add a tab in contract view
		// 'user'             to add a tab in user view
		// 'group'            to add a tab in group view
		// 'contact'          to add a tab in contact view
		// 'payment'		  to add a tab in payment view
		// 'payment_supplier' to add a tab in supplier payment view
		// 'categories_x'	  to add a tab in category view (replace 'x' by type of category (0=product, 1=supplier, 2=customer, 3=member)
		// 'opensurveypoll'	  to add a tab in opensurvey poll view
        $this->tabs = array();
        // Dictionaries
	    if (! isset($conf->mymodule->enabled))
        {
        	$conf->mymodule=new stdClass();
        	$conf->mymodule->enabled=0;
        }
		$this->dictionaries=array();
        /* Example:
        if (! isset($conf->mymodule->enabled)) $conf->mymodule->enabled=0;	// This is to avoid warnings
        $this->dictionaries=array(
            'langs'=>'mylangfile@mymodule',
            'tabname'=>array(MAIN_DB_PREFIX."table1", MAIN_DB_PREFIX."table2", MAIN_DB_PREFIX."table3"), 		// List of tables we want to see into dictonnary editor
            'tablib'=>array("Table1", "Table2", "Table3"), 													// Label of tables
            'tabsql'=>array('SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table1 as f', 'SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table2 as f', 'SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table3 as f'), 	// Request to select fields
            'tabsqlsort'=>array("label ASC", "label ASC", "label ASC"), 																					// Sort order
            'tabfield'=>array("code, label", "code, label", "code, label"), 																					// List of fields (result of select to show dictionary)
            'tabfieldvalue'=>array("code, label", "code, label", "code, label"), 																				// List of fields (list of fields to edit a record)
            'tabfieldinsert'=>array("code, label", "code, label", "code, label"), 																			// List of fields (list of fields for insert)
            'tabrowid'=>array("rowid", "rowid", "rowid"), 																									// Name of columns with primary key (try to always name it 'rowid')
            'tabcond'=>array($conf->mymodule->enabled, $conf->mymodule->enabled, $conf->mymodule->enabled)												// Condition to show each dictionary
        );
        */
        // Boxes
		// Add here list of php file(s) stored in core/boxes that contains class to show a box.
        $this->boxes = array(
            0 => array(
        'file' => 'box_approval.php@timesheet',
        'note' => 'timesheetApproval',
        'enabledbydefaulton' => 'Home'));			// List of boxes
		// Example:
		//$this->boxes=array(array(0=>array('file'=>'myboxa.php', 'note'=>'', 'enabledbydefaulton'=>'Home'), 1=>array('file'=>'myboxb.php', 'note'=>''), 2=>array('file'=>'myboxc.php', 'note'=>'')););
		// Permissions
		$this->rights = array();		// Permission array used by this module
		$r=0;
		 $this->rights[$r][0] = 86100200;				// Permission id (must not be already used)
		 $this->rights[$r][1] = 'TimesheetUser';	// Permission label
		 $this->rights[$r][3] = 0;					// Permission by default for new user (0/1)
		 $this->rights[$r][4] = 'user';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 //$this->rights[$r][5] = 'team';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 $r++;
		//$r=0;
		 $this->rights[$r][0] = 86100201;				// Permission id (must not be already used)
		 $this->rights[$r][1] = 'TeamApprover';	// Permission label
		 $this->rights[$r][3] = 0;					// Permission by default for new user (0/1)
		 $this->rights[$r][4] = 'approval';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 $this->rights[$r][5] = 'team';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 $r++;
		 $this->rights[$r][0] = 86100202;				// Permission id (must not be already used)
		 $this->rights[$r][1] = 'ApprovalAdmin';	// Permission label
		 $this->rights[$r][3] = 0;					// Permission by default for new user (0/1)
		 $this->rights[$r][4] = 'approval';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 $this->rights[$r][5] = 'admin';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		
                 $r++;		// Add here list of permission defined by an id, a label, a boolean and two constant strings.
		 $this->rights[$r][0] = 86100203;				// Permission id (must not be already used)
		 $this->rights[$r][1] = 'ExportRead';	// Permission label
		 $this->rights[$r][3] = 0;					// Permission by default for new user (0/1)
		 $this->rights[$r][4] = 'read';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 //$this->rights[$r][5] = 'admin';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 $r++;		// Add here list of permission defined by an id, a label, a boolean and two constant strings.
                 $this->rights[$r][0] = 86100205;				// Permission id (must not be already used)
		 $this->rights[$r][1] = 'AttendanceUser';	// Permission label
		 $this->rights[$r][3] = 0;					// Permission by default for new user (0/1)
		 $this->rights[$r][4] = 'attendance';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 $this->rights[$r][5] = 'user';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 $r++;
                 $this->rights[$r][0] = 86100206;				// Permission id (must not be already used)
		 $this->rights[$r][1] = 'AttendanceAdmin';	// Permission label
		 $this->rights[$r][3] = 0;					// Permission by default for new user (0/1)
		 $this->rights[$r][4] = 'attendance';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 $this->rights[$r][5] = 'admin';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		 $r++;
// Example:
		// $this->rights[$r][0] = 2000;				// Permission id (must not be already used)
		// $this->rights[$r][1] = 'Permision label';	// Permission label
		// $this->rights[$r][3] = 1;					// Permission by default for new user (0/1)
		// $this->rights[$r][4] = 'level1';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		// $this->rights[$r][5] = 'level2';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		// $r++;
		// Main menu entries
		$this->menu = array();			// List of menus to add
		$r=0;
		// Add here entries to declare new menus
		//
		// Example to declare a new Top Menu entry and its Left menu entry:
		$this->menu[$r]=array(	'fk_menu'=>0,             // Put 0 if this is a top menu
									'type'=>'top', 			                // This is a Top menu entry
									'titre'=>'Timesheet',
									'mainmenu'=>'timesheet',
                                                                        'leftmenu'=>'timesheet',
									'url'=>'/timesheet/Timesheet.php',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>100,
									'enabled'=>'$user->rights->timesheet->user && !($user->rights->timesheet->attendance->user && $conf->global->TIMESHEET_ATTENDANCE)', 	// Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled.
									'perms'=>'1', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);				                // 0=Menu for internal users, 1=external users, 2=both
		 $r++;
	
                  $this->menu[$r]=array(	'fk_menu'=>0, 		    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
									'type'=>'top', 			                // This is a Left menu entry
									'titre'=>'Attendance',
									'mainmenu'=>'timesheet',
                                                                        'leftmenu'=>'attendance',
									'url'=>'/timesheet/AttendanceClock.php',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>200,
									'enabled'=>'$user->rights->timesheet->user && $user->rights->timesheet->attendance->user && $conf->global->TIMESHEET_ATTENDANCE',
									'perms'=>'1', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);
                  $r++;
		$this->menu[$r]=array(	'fk_menu'=>'fk_mainmenu=timesheet',             // Put 0 if this is a top menu
									'type'=>'left', 			                // This is a Top menu entry
									'titre'=>'Timesheet',
									'mainmenu'=>'timesheet',
                                                                        'leftmenu'=>'timesheet',
									'url'=>'/timesheet/Timesheet.php?#',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>100,
									'enabled'=>'$conf->timesheet->enabled', 	// Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled.
									'perms'=>'1', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);				                // 0=Menu for internal users, 1=external users, 2=both
		 $r++;
	
                  $this->menu[$r]=array(	'fk_menu'=>'fk_mainmenu=timesheet', 		    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
									'type'=>'left', 			                // This is a Left menu entry
									'titre'=>'Attendance',
									'mainmenu'=>'timesheet',
                                                                        'leftmenu'=>'attendance',
									'url'=>'/timesheet/AttendanceClock.php?#',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>200,
									'enabled'=>'$user->rights->timesheet->attendance->user',
									'perms'=>'1', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);
                  $r++;
                  $this->menu[$r]=array(	'fk_menu'=>'fk_mainmenu=timesheet, fk_leftmenu=attendance', 		    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
									'type'=>'left', 			                // This is a Left menu entry
									'titre'=>'AttendanceAdmin',
									'mainmenu'=>'timesheet',
                                                                        'leftmenu'=>'attendance',
									'url'=>'/timesheet/AttendanceEventAdmin.php',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>210,
									'enabled'=>'$user->rights->timesheet->attendance->admin',
									'perms'=>'1', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);
                  $r++;
                $this->menu[$r]=array(	'fk_menu'=>'fk_mainmenu=timesheet, fk_leftmenu=timesheet', 		    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
									'type'=>'left', 			                // This is a Left menu entry
									'titre'=>'userReport',
									'mainmenu'=>'timesheet',
                                                                        'leftmenu'=>'timesheet',
									'url'=>'/timesheet/TimesheetReportUser.php',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>130,
									'enabled'=>'$conf->timesheet->enabled', // Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
									'perms'=>'1', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);
                  $r++;
                $this->menu[$r]=array(	'fk_menu'=>'fk_mainmenu=timesheet, fk_leftmenu=timesheet', 		    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
									'type'=>'left', 			                // This is a Left menu entry
									'titre'=>'Timesheetwhitelist',
									'mainmenu'=>'timesheet',
                                                                        'leftmenu'=>'Timesheet',
									'url'=>'/timesheet/TimesheetFavouriteAdmin.php',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>110,
									'enabled'=>'$conf->global->TIMESHEET_WHITELIST==1', // Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
									'perms'=>'1', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);
                  $r++;
                  $this->menu[$r]=array(	'fk_menu'=>'fk_mainmenu=project, fk_leftmenu=projects', 		    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
									'type'=>'left', 			                // This is a Left menu entry
									'titre'=>'projectReport',
									'mainmenu'=>'project',
                                                                        'leftmenu'=>'projectReport',
									'url'=>'/timesheet/TimesheetReportProject.php',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>120,
									'enabled'=>'$conf->timesheet->enabled', // Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
									'perms'=>'1', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);
                   $r++;
                  $this->menu[$r]=array(	'fk_menu'=>'fk_mainmenu=project, fk_leftmenu=projects', 		    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
									'type'=>'left', 			                // This is a Left menu entry
									'titre'=>'projectInvoice',
									'mainmenu'=>'project',
                                                                        'leftmenu'=>'projectInvoice',
									'url'=>'/timesheet/TimesheetProjectInvoice.php',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>121,
									'enabled'=>'$conf->timesheet->enabled', // Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
									'perms'=>'$user->rights->facture->creer', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);
                  $r++;
                    $this->menu[$r]=array(	'fk_menu'=>'fk_mainmenu=timesheet', 		    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
									'type'=>'left', 			                // This is a Left menu entry
									'titre'=>'Timesheetapproval',
									'mainmenu'=>'timesheet',
                                                                        'leftmenu'=>'Timesheetapproval',
									'url'=>'/timesheet/TimesheetTeamApproval.php',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>130,
									'enabled'=>'$user->rights->timesheet->approval', // Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
									'perms'=>'$user->rights->timesheet->approval', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);
                  $r++;
                   $this->menu[$r]=array(	'fk_menu'=>'fk_mainmenu=timesheet, fk_leftmenu=Timesheetapproval', 		    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
									'type'=>'left', 			                // This is a Left menu entry
									'titre'=>'Adminapproval',
									'mainmenu'=>'timesheet',
                                                                        'leftmenu'=>'Adminapproval',
									'url'=>'/timesheet/TimesheetUserTasksAdmin.php?action=list&sortfield=t.date_start&sortorder=desc',
									'langs'=>'timesheet@timesheet', 	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>131,
									'enabled'=>'$user->rights->timesheet->approval->admin', // Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
									'perms'=>'$user->rights->timesheet->approval->admin', 			                // Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
									'target'=>'',
									'user'=>2);
                  $r++;
// impoort
/* 		$r++;
		$this->import_code[$r]=$this->rights_class.'_'.$r;
		$this->import_label[$r]="ImportDataset_Kimai";	// Translation key
		$this->import_icon[$r]='project';
		$this->import_entities_array[$r]=array('pt.fk_user'=>'user');		// We define here only fields that use another icon that the one defined into import_icon
		$this->import_tables_array[$r]=array('ptt'=>MAIN_DB_PREFIX.'project_task_time');
		$this->import_fields_array[$r]=array('ptt.fk_task'=>"ThirdPartyName*", 'ptt.fk_user'=>"User*");
		$this->import_convertvalue_array[$r]=array(
				'ptt.fk_task'=>array('rule'=>'fetchidfromref', 'classfile'=>'/timesheet/class/timesheet.class.php', 'class'=>'Timesheet', 'method'=>'fetch', 'element'=>'ThirdParty'),
				'sr.fk_user'=>array('rule'=>'fetchidfromref', 'classfile'=>'/user/class/user.class.php', 'class'=>'User', 'method'=>'fetch', 'element'=>'User')
		);
		$this->import_examplevalues_array[$r]=array('sr.fk_soc'=>"MyBigCompany", 'sr.fk_user'=>"login");*/
		// Exports
		//$r=1;
		// Example:
		// $this->export_code[$r]=$this->rights_class.'_'.$r;
		// $this->export_label[$r]='CustomersInvoicesAndInvoiceLines';	// Translation key (used only if key ExportDataset_xxx_z not found)
        // $this->export_enabled[$r]='1';// Condition to show export in list (ie: '$user->id==3'). Set to 1 to always show when module is enabled.
		// $this->export_permission[$r]=array(array("facture", "facture", "export"));
		// $this->export_fields_array[$r]=array('s.rowid'=>"IdCompany", 's.nom'=>'CompanyName', 's.address'=>'Address', 's.zip'=>'Zip', 's.town'=>'Town', 's.fk_pays'=>'Country', 's.phone'=>'Phone', 's.siren'=>'ProfId1', 's.siret'=>'ProfId2', 's.ape'=>'ProfId3', 's.idprof4'=>'ProfId4', 's.code_compta'=>'CustomerAccountancyCode', 's.code_compta_fournisseur'=>'SupplierAccountancyCode', 'f.rowid'=>"InvoiceId", 'f.facnumber'=>"InvoiceRef", 'f.datec'=>"InvoiceDateCreation", 'f.datef'=>"DateInvoice", 'f.total'=>"TotalHT", 'f.total_ttc'=>"TotalTTC", 'f.tva'=>"TotalVAT", 'f.paye'=>"InvoicePaid", 'f.fk_statut'=>'InvoiceStatus', 'f.note'=>"InvoiceNote", 'fd.rowid'=>'LineId', 'fd.description'=>"LineDescription", 'fd.price'=>"LineUnitPrice", 'fd.tva_tx'=>"LineVATRate", 'fd.qty'=>"LineQty", 'fd.total_ht'=>"LineTotalHT", 'fd.total_tva'=>"LineTotalTVA", 'fd.total_ttc'=>"LineTotalTTC", 'fd.date_start'=>"DateStart", 'fd.date_end'=>"DateEnd", 'fd.fk_product'=>'ProductId', 'p.ref'=>'ProductRef');
		// $this->export_entities_array[$r]=array('s.rowid'=>"company", 's.nom'=>'company', 's.address'=>'company', 's.zip'=>'company', 's.town'=>'company', 's.fk_pays'=>'company', 's.phone'=>'company', 's.siren'=>'company', 's.siret'=>'company', 's.ape'=>'company', 's.idprof4'=>'company', 's.code_compta'=>'company', 's.code_compta_fournisseur'=>'company', 'f.rowid'=>"invoice", 'f.facnumber'=>"invoice", 'f.datec'=>"invoice", 'f.datef'=>"invoice", 'f.total'=>"invoice", 'f.total_ttc'=>"invoice", 'f.tva'=>"invoice", 'f.paye'=>"invoice", 'f.fk_statut'=>'invoice', 'f.note'=>"invoice", 'fd.rowid'=>'invoice_line', 'fd.description'=>"invoice_line", 'fd.price'=>"invoice_line", 'fd.total_ht'=>"invoice_line", 'fd.total_tva'=>"invoice_line", 'fd.total_ttc'=>"invoice_line", 'fd.tva_tx'=>"invoice_line", 'fd.qty'=>"invoice_line", 'fd.date_start'=>"invoice_line", 'fd.date_end'=>"invoice_line", 'fd.fk_product'=>'product', 'p.ref'=>'product');
		// $this->export_sql_start[$r]='SELECT DISTINCT ';
		// $this->export_sql_end[$r]  =' FROM ('.MAIN_DB_PREFIX.'facture as f, '.MAIN_DB_PREFIX.'facturedet as fd, '.MAIN_DB_PREFIX.'societe as s)';
		// $this->export_sql_end[$r] .=' LEFT JOIN '.MAIN_DB_PREFIX.'product as p on (fd.fk_product = p.rowid)';
		// $this->export_sql_end[$r] .=' WHERE f.fk_soc = s.rowid AND f.rowid = fd.fk_facture';
		// $this->export_sql_order[$r] .=' ORDER BY s.nom';
		// $r++;
	}
	/**
	 *		Function called when module is enabled.
	 *		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *		It also creates data directories
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function init($options='')
	{
                global $db, $conf;
                $result=$this->_load_tables('/timesheet/sql/');
		$sql = array();
                $sql[0] = 'DELETE FROM '.MAIN_DB_PREFIX.'project_task_timesheet';
                $sql[0].= ' WHERE status IN (1, 5)';//'DRAFT', 'REJECTED'
                $sql[1] ="DELETE FROM ".MAIN_DB_PREFIX."document_model WHERE nom = 'rat' AND type='timesheetReport' AND entity = ".$conf->entity;
                $sql[2] ="INSERT INTO ".MAIN_DB_PREFIX."document_model (nom, type, entity) VALUES('rat', 'timesheetReport', ".$conf->entity.")";
                dolibarr_set_const($db, "TIMESHEET_VERSION", $this->version, 'chaine', 0, '', $conf->entity);
                include_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
		$extrafields = new ExtraFields($this->db);
                $extrafields->addExtraField('fk_service', "DefaultService", 'sellist',  1, '', 'user',       0, 0, '', array('options'=>array('product:ref|label:rowid::tosell=1 AND fk_product_type=1'=>'N')), 1, 1, 3, 0, '', 0, 'timesheet@ptimesheet', '$conf->timesheet->enabled');
                $extrafields->addExtraField('fk_service', "DefaultService", 'sellist',  1, '', 'projet_task', 0, 0, '', array('options'=>array('product:ref|label:rowid::tosell=1 AND fk_product_type=1'=>'N')), 1, 1, 3, 0, '', 0, 'timesheet@ptimesheet', '$conf->timesheet->enabled');
                $extrafields->addExtraField('invoiceable', "Invoiceable", 'boolean',  1, '', 'projet_task', 0, 0, '', '', 1, 1, 1, 0, '', 0, 'timesheet@ptimesheet', '$conf->timesheet->enabled');
		return $this->_init($sql, $options);
	}
	/**
	 *		Function called when module is disabled.
	 *      Remove from database constants, boxes and permissions from Dolibarr database.
	 *		Data directories are not deleted
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function remove($options='')
	{
		return $this->_remove($sql, $options);
	}
}