<?php



/*

|--------------------------------------------------------------------------

| Application Routes

|--------------------------------------------------------------------------

|

| Here is where you can register all of the routes for an application.

| It's a breeze. Simply tell Laravel the URIs it should respond to

| and give it the controller to call when that URI is requested.

|

*/



Route::get('/', 'user\UserauthenticateController@login');



Route::get('home', 'user\UserauthenticateController@login');



Route::controllers([

	'auth' => 'Auth\AuthController',

	'password' => 'Auth\PasswordController',

]);





Route::get('/admin', 'admin\AdminauthenticateController@login');

Route::post('/admin/login', 'admin\AdminauthenticateController@postLogin');



Route::get('/admin/logout', 'admin\AdminController@logout');



Route::get('/admin/profile', 'admin\AdminController@profile');

Route::get('/admin/vat_profile', 'admin\AdminController@vatprofile');

Route::get('/admin/email_settings', 'admin\AdminController@email_settings');

Route::get('/admin/p30_profile', 'admin\AdminController@p30profile');



Route::post('/admin/update_admin_setting', 'admin\AdminController@update_admin_setting');

Route::post('/admin/update_user_notification', 'admin\AdminController@update_user_notification');

Route::post('/admin/update_user_signature', 'admin\AdminController@update_user_signature');



Route::post('/admin/update_user_setting', 'admin\AdminController@update_user_setting');

Route::post('/admin/update_email_setting', 'admin\AdminController@update_email_setting');

Route::get('/admin/manage_year', 'admin\YearController@manageyear');

Route::get('/admin/deactive_year/{id?}', 'admin\YearController@deactiveyear');

Route::get('/admin/active_year/{id?}', 'admin\YearController@activeyear');

Route::post('/admin/add_year/', 'admin\YearController@addyear');

Route::get('/admin/delete_year/{id?}', 'admin\YearController@deleteyear');

Route::post('/admin/edit_year/{id?}', 'admin\YearController@edityear');

Route::post('/admin/update_year/', 'admin\YearController@updateyear');

Route::post('/admin/check_year/', 'admin\YearController@checkyear');





Route::get('/admin/manage_user', 'admin\UserController@manageuser');

Route::get('/admin/deactive_user/{id?}', 'admin\UserController@deactiveuser');

Route::get('/admin/active_user/{id?}', 'admin\UserController@activeuser');

Route::post('/admin/add_user/', 'admin\UserController@adduser');

Route::get('/admin/delete_user/{id?}', 'admin\UserController@deleteuser');

Route::post('/admin/edit_user/{id?}', 'admin\UserController@edituser');

Route::post('/admin/update_user/', 'admin\UserController@updateuser');



Route::get('/admin/central_locations', 'admin\AdminController@central_locations');

Route::post('/admin/update_central_locations', 'admin\AdminController@update_central_locations');

Route::post('/admin/update_central_locations_form', 'admin\AdminController@update_central_locations_form');





Route::get('/admin/manage_task/', 'admin\TaskyearController@taskyear');

Route::post('/admin/add_taskyear/', 'admin\TaskyearController@addtaskyear');

Route::get('/admin/deactive_taskyear/{id?}', 'admin\TaskyearController@deactivetaskyear');

Route::get('/admin/active_taskyear/{id?}', 'admin\TaskyearController@activetaskyear');

Route::get('/admin/delete_taskyear/{id?}', 'admin\TaskyearController@deletetaskyear');

Route::get('/admin/edit_taskyear/{id?}', 'admin\TaskyearController@edittaskyear');

Route::post('/admin/update_taskyear/', 'admin\TaskyearController@updatetaskyear');



Route::post('/user/login', 'user\UserauthenticateController@postLogin');

Route::get('/user/logout', 'user\UserController@logout');

Route::get('/user/manage_week', 'user\UserController@manageweek');

Route::get('/user/downloadpdf', 'user\UserController@downloadpdf');

Route::get('/user/week_manage/{id?}', 'user\UserController@weekmanage');

Route::get('/user/select_week/{id?}', 'user\UserController@selectweek');

Route::post('/user/add_new_task/', 'user\UserController@addnewtask');

Route::get('/user/delete_task/{id?}', 'user\UserController@deletetask');



Route::get('/user/task_enterhours', 'user\UserController@task_enterhours');

Route::get('/user/task_started_checkbox', 'user\UserController@task_started_checkbox');



Route::get('/user/notify_tasks', 'user\UserController@notify_tasks');

Route::get('/user/notify_tasks_month', 'user\UserController@notify_tasks_month');
Route::post('/user/taskmanager_upload_images', 'user\UserController@taskmanager_upload_images');
Route::post('/user/remove_dropzone_attachment', 'user\UserController@remove_dropzone_attachment');

Route::get('/user/task_holiday', 'user\UserController@task_holiday');

Route::get('/user/task_process', 'user\UserController@task_process');

Route::get('/user/task_payslips', 'user\UserController@task_payslips');

Route::get('/user/task_email', 'user\UserController@task_email');

Route::get('/user/task_upload', 'user\UserController@task_upload');

Route::get('/user/task_date_update', 'user\UserController@task_date_update');

Route::get('/user/task_email_update', 'user\UserController@task_email_update');

Route::get('/user/task_users_update', 'user\UserController@task_users_update');

Route::get('/user/task_comments_update', 'user\UserController@task_comments_update');

Route::get('/user/task_liability_update', 'user\UserController@task_liability_update');

Route::get('/user/task_classified_update', 'user\UserController@task_classified_update');

Route::post('/user/task_image_upload', 'user\UserController@task_image_upload');

Route::post('/user/task_notepad_upload', 'user\UserController@task_notepad_upload');

Route::post('/user/copy_task', 'user\UserController@copy_task');

Route::get('/user/task_delete_image', 'user\UserController@task_delete_image');

Route::get('/user/task_delete_all_image', 'user\UserController@task_delete_all_image');

Route::get('/user/task_delete_all_image_attachments', 'user\UserController@task_delete_all_image_attachments');

Route::get('/user/task_status_update', 'user\UserController@task_status_update');



Route::get('/user/get_week_by_year', 'user\UserController@get_week_by_year');

Route::get('/user/get_month_by_year', 'user\UserController@get_month_by_year');

Route::post('/user/email_unsent_files', 'user\UserController@email_unsent_files');

Route::get('/user/email_report_pdf', 'user\UserController@email_report_pdf');

Route::get('/user/email_notify_pdf', 'user\UserController@email_notify_pdf');

Route::get('/user/email_notify_tasks_pdf', 'user\UserController@email_notify_tasks_pdf');

Route::get('/user/email_notify_pdf_month', 'user\UserController@email_notify_pdf_month');

Route::post('/user/email_report_send', 'user\UserController@email_report_send');

Route::get('/user/close_create_new_week/{id?}', 'user\UserController@close_create_new_week');

Route::get('/user/close_create_new_month/{id?}', 'user\UserController@close_create_new_month');



Route::get('/user/manage_month', 'user\UserController@managemonth');

Route::get('/user/month_manage/{id?}', 'user\UserController@monthmanage');

Route::get('/user/select_month/{id?}', 'user\UserController@selectmonth');

Route::post('/user/add_new_task_month/', 'user\UserController@addnewtask_month');

Route::get('/user/email_report_pdf_month', 'user\UserController@email_report_pdf_month');



Route::get('/user/alltask_report_pdf_month', 'user\UserController@alltask_report_pdf_month');

Route::get('/user/task_complete_report_pdf_month', 'user\UserController@task_complete_report_pdf_month');

Route::get('/user/task_incomplete_report_pdf_month', 'user\UserController@task_incomplete_report_pdf_month');



Route::get('/user/alltask_report_pdf', 'user\UserController@alltask_report_pdf');

Route::get('/user/task_complete_report_pdf', 'user\UserController@task_complete_report_pdf');

Route::get('/user/task_incomplete_report_pdf', 'user\UserController@task_incomplete_report_pdf');





Route::get('/user/edit_task_name', 'user\UserController@edit_task_name');

Route::get('/user/edit_email_unsent_files', 'user\UserController@edit_email_unsent_files');



Route::post('/user/edit_task_details', 'user\UserController@edit_task_details');



Route::post('/user/update_incomplete_status', 'user\UserController@update_incomplete_status');

Route::post('/user/update_incomplete_status_month', 'user\UserController@update_incomplete_status_month');



Route::get('/user/vat_clients', 'user\UserController@vatclients');

Route::get('/user/deactive_vat_clients/{id?}', 'user\UserController@deactivevatclients');

Route::get('/user/active_vat_clients/{id?}', 'user\UserController@activevatclients');

Route::post('/user/edit_vat_clients/{id?}', 'user\UserController@editvatclients');

Route::post('/user/add_vat_clients', 'user\UserController@addvatclients');

Route::post('/user/update_vat_clients/', 'user\UserController@updatevatclients');



Route::get('/user/check_client_email', 'user\UserController@checkclientemail');

Route::get('/user/check_client_taxnumber', 'user\UserController@checkclienttaxnumber');

Route::post('/user/import_form','user\UserController@import_form');

Route::get('/user/import_form_one','user\UserController@import_form_one');



Route::post('/user/compare_form','user\UserController@compare_form');

Route::get('/user/compare_form_one','user\UserController@compare_form_one');



Route::get('/user/vat_notifications','user\UserController@vat_notifications');



Route::get('/user/import_sessions','user\UserController@import_sessions');

Route::get('/user/import_sessions_one','user\UserController@import_sessions_one');



Route::get('/user/email_vatnotifications', 'user\UserController@email_vatnotifications');

Route::get('/user/email_sents', 'user\UserController@email_sents');

Route::get('/user/email_sents_save_pdf', 'user\UserController@email_sents_save_pdf');



Route::get('/user/pdf_without_email','user\UserController@pdf_without_email');

Route::get('/user/pdf_with_email','user\UserController@pdf_with_email');

Route::get('/user/pdf_disabled','user\UserController@pdf_disabled');

Route::post('/user/save_disclose_liability','user\UserController@save_disclose_liability');

Route::post('/user/getclientcompanyname','user\UserController@getclientcompanyname');

Route::post('/user/getclientemail','user\UserController@getclientemail');

Route::post('/user/getclientemail_secondary','user\UserController@getclientemail_secondary');



/*----------------------P30 START----------------------*/

Route::get('/user/p30', 'user\P30Controller@p30');

Route::get('/user/p30month_manage/{id?}', 'user\P30Controller@p30monthmanage');

Route::get('/user/p30_select_month/{id?}', 'user\P30Controller@p30selectmonth');

Route::get('/user/p30_review_month/{id?}', 'user\P30Controller@review_month');

Route::get('/user/p30_close_create_new_month/{id?}', 'user\P30Controller@close_create_new_month');

Route::post('/user/p30_tasklevel_update/{id?}', 'user\P30Controller@p30tasklevelupdate');

Route::post('/user/p30_period_update/{id?}', 'user\P30Controller@p30periodupdate');



Route::get('/user/pay_p30', 'user\P30Controller@payp30');

Route::get('/user/na_p30', 'user\P30Controller@nap30');



Route::get('/user/email_p30', 'user\P30Controller@emailp30');

Route::post('/user/p30_task_image_upload', 'user\P30Controller@p30_task_image_upload');

Route::post('/user/p30_task_automatic_image_upload', 'user\P30Controller@p30_task_automatic_image_upload');

Route::get('/user/p30_task_delete_image', 'user\P30Controller@p30_task_delete_image');

Route::get('/user/p30_task_delete_xml', 'user\P30Controller@p30_task_delete_xml');



Route::get('/user/p30_task_liability_update', 'user\P30Controller@task_liability_update');

Route::get('/user/p30_edit_email_unsent_files', 'user\P30Controller@p30_edit_email_unsent_files');

Route::post('/user/p30_email_unsent_files', 'user\P30Controller@p30_email_unsent_files');

Route::get('/user/p30_task_status_update', 'user\P30Controller@p30_task_status_update');

Route::post('/user/p30_report_task', 'user\P30Controller@p30_report_task');

Route::post('/user/download_p30_pdf_report', 'user\P30Controller@download_p30_pdf_report');

Route::post('/user/import_p30_review_due', 'user\P30Controller@import_p30_review_due');

Route::get('/user/download_p30_review', 'user\P30Controller@download_p30_review');

Route::post('/user/update_p30_incomplete_status_month', 'user\P30Controller@update_p30_incomplete_status_month');

Route::post('/user/update_p30_na_status_month', 'user\P30Controller@update_p30_na_status_month');





Route::get('/admin/p30_task_leval', 'admin\P30Controller@tasklevel');

Route::get('/admin/deactive_p30_tasklevel/{id?}', 'admin\P30Controller@deactivetasklevel');

Route::get('/admin/active_p30_tasklevel/{id?}', 'admin\P30Controller@activetasklevel');

Route::post('/admin/add_p30_tasklevel/', 'admin\P30Controller@addtasklevel');

Route::post('/admin/edit_p30_tasklevel/{id?}', 'admin\P30Controller@edittasklevel');

Route::post('/admin/update_p30_tasklevel/', 'admin\P30Controller@updatetasklevel');



Route::get('/admin/p30_period', 'admin\P30Controller@period');

Route::get('/admin/deactive_p30_period/{id?}', 'admin\P30Controller@deactiveperiod');

Route::get('/admin/active_p30_period/{id?}', 'admin\P30Controller@activeperiod');

Route::post('/admin/add_p30_period/', 'admin\P30Controller@addperiod');

Route::post('/admin/edit_p30_period/{id?}', 'admin\P30Controller@editperiod');

Route::post('/admin/update_p30_period/', 'admin\P30Controller@updateperiod');



Route::get('/admin/period_sort_order/', 'admin\P30Controller@period_sort_order');



Route::get('/admin/p30_due_date', 'admin\P30Controller@duedate');

Route::post('/admin/update_p30_duedate/', 'admin\P30Controller@updateduedate');





/*----------------------GBSCO P30 START----------------------*/

Route::get('/user/gbs_p30', 'user\Gbs_P30Controller@gbs_p30');

Route::get('/user/gbs_p30month_manage/{id?}', 'user\Gbs_P30Controller@gbs_p30monthmanage');

Route::get('/user/gbs_p30_select_month/{id?}', 'user\Gbs_P30Controller@gbs_p30selectmonth');

Route::get('/user/gbs_p30_review_month/{id?}', 'user\Gbs_P30Controller@review_month');

Route::get('/user/gbs_p30_close_create_new_month/{id?}', 'user\Gbs_P30Controller@close_create_new_month');

Route::post('/user/gbs_p30_tasklevel_update/{id?}', 'user\Gbs_P30Controller@gbs_p30tasklevelupdate');

Route::post('/user/gbs_p30_period_update/{id?}', 'user\Gbs_P30Controller@gbs_p30periodupdate');



Route::get('/user/pay_gbs_p30', 'user\Gbs_P30Controller@paygbs_p30');

Route::get('/user/na_gbs_p30', 'user\Gbs_P30Controller@nagbs_p30');



Route::get('/user/email_gbs_p30', 'user\Gbs_P30Controller@emailgbs_p30');

Route::post('/user/gbs_p30_task_image_upload', 'user\Gbs_P30Controller@gbs_p30_task_image_upload');

Route::post('/user/gbs_p30_task_automatic_image_upload', 'user\Gbs_P30Controller@gbs_p30_task_automatic_image_upload');

Route::get('/user/gbs_p30_task_delete_image', 'user\Gbs_P30Controller@gbs_p30_task_delete_image');

Route::get('/user/gbs_p30_task_delete_xml', 'user\Gbs_P30Controller@gbs_p30_task_delete_xml');



Route::get('/user/gbs_p30_task_liability_update', 'user\Gbs_P30Controller@task_liability_update');

Route::get('/user/gbs_p30_edit_email_unsent_files', 'user\Gbs_P30Controller@gbs_p30_edit_email_unsent_files');

Route::post('/user/gbs_p30_email_unsent_files', 'user\Gbs_P30Controller@gbs_p30_email_unsent_files');

Route::get('/user/gbs_p30_task_status_update', 'user\Gbs_P30Controller@gbs_p30_task_status_update');

Route::post('/user/gbs_p30_report_task', 'user\Gbs_P30Controller@gbs_p30_report_task');

Route::post('/user/download_gbs_p30_pdf_report', 'user\Gbs_P30Controller@download_gbs_p30_pdf_report');

Route::post('/user/import_gbs_p30_review_due', 'user\Gbs_P30Controller@import_gbs_p30_review_due');

Route::get('/user/download_gbs_p30_review', 'user\Gbs_P30Controller@download_gbs_p30_review');

Route::post('/user/update_gbs_p30_incomplete_status_month', 'user\Gbs_P30Controller@update_gbs_p30_incomplete_status_month');



Route::get('/admin/gbs_p30_task_leval', 'admin\Gbs_P30Controller@tasklevel');

Route::get('/admin/deactive_gbs_p30_tasklevel/{id?}', 'admin\Gbs_P30Controller@deactivetasklevel');

Route::get('/admin/active_gbs_p30_tasklevel/{id?}', 'admin\Gbs_P30Controller@activetasklevel');

Route::post('/admin/add_gbs_p30_tasklevel/', 'admin\Gbs_P30Controller@addtasklevel');

Route::post('/admin/edit_gbs_p30_tasklevel/{id?}', 'admin\Gbs_P30Controller@edittasklevel');

Route::post('/admin/update_gbs_p30_tasklevel/', 'admin\Gbs_P30Controller@updatetasklevel');



Route::get('/admin/gbs_p30_period', 'admin\Gbs_P30Controller@period');

Route::get('/admin/deactive_gbs_p30_period/{id?}', 'admin\Gbs_P30Controller@deactiveperiod');

Route::get('/admin/active_gbs_p30_period/{id?}', 'admin\Gbs_P30Controller@activeperiod');

Route::post('/admin/add_gbs_p30_period/', 'admin\Gbs_P30Controller@addperiod');

Route::post('/admin/edit_gbs_p30_period/{id?}', 'admin\Gbs_P30Controller@editperiod');

Route::post('/admin/update_gbs_p30_period/', 'admin\Gbs_P30Controller@updateperiod');



Route::get('/admin/gbs_period_sort_order/', 'admin\Gbs_P30Controller@gbs_period_sort_order');



Route::get('/admin/gbs_p30_due_date', 'admin\Gbs_P30Controller@duedate');

Route::post('/admin/update_gbs_p30_duedate/', 'admin\Gbs_P30Controller@updateduedate');



/*----------------------RCT START----------------------*/

Route::get('/admin/manage_rctclients', 'admin\RctClientsController@managerctclients');

Route::get('/admin/deactive_rctclients/{id?}', 'admin\RctClientsController@deactiverctclients');

Route::get('/admin/active_rctclients/{id?}', 'admin\RctClientsController@activerctclients');

Route::post('/admin/add_rctclients/', 'admin\RctClientsController@addrctclients');

Route::get('/admin/delete_rctclients/{id?}', 'admin\RctClientsController@deleterctclients');

Route::post('/admin/edit_rctclients/{id?}', 'admin\RctClientsController@editrctclients');

Route::post('/admin/update_rctclients/', 'admin\RctClientsController@updaterctclients');

Route::get('/admin/rctclient_checkemail/', 'admin\RctClientsController@client_checkemail');

Route::get('/admin/rctclient_checktax/', 'admin\RctClientsController@client_checktax');



Route::get('/user/rctclient_checkemail/', 'user\RctController@client_checkemail');

Route::get('/user/rctclient_checktax/', 'user\RctController@client_checktax');



Route::get('/admin/manage_rctemail_salution', 'admin\SalutionController@manage_salution');

Route::post('/admin/edit_rctsalution/{id?}', 'admin\SalutionController@editsalution');

Route::post('/admin/update_rctsalution/', 'admin\SalutionController@updatesalution');



Route::get('/admin/manage_rctbackground', 'admin\LetterController@manage_letterpad');

Route::post('/admin/edit_rctletterpad/{id?}', 'admin\LetterController@editletterpad');

Route::post('/admin/update_rctletterpad/', 'admin\LetterController@updateletterpad');



Route::get('/user/rctclients', 'user\RctController@rctclients');

Route::post('/user/add_rctclients/', 'user\RctController@addrctclients');

Route::get('/user/rctclient_hidden/{id?}', 'user\RctController@clienthidden');

Route::post('/user/edit_rctclients/{id?}', 'user\RctController@editrctclients');

Route::post('/user/update_rctclients/', 'user\RctController@updaterctclients');



Route::get('/user/expand_rctclient/{id?}', 'user\RctController@expandclient');

Route::post('/user/rct_add_new_item/', 'user\RctController@addnewitem');



Route::post('/user/rctpaginate_response', 'user\RctController@paginate_response');



Route::post('/user/rctclient_expand_type_update', 'user\RctController@clientexpandtypeupdate');

Route::post('/user/rctclient_expand_sub_update', 'user\RctController@clientexpandsubupdate');

Route::post('/user/rctclient_expand_sub_rctno', 'user\RctController@clientexpandsubrctno');

Route::post('/user/rctclient_expand_reference', 'user\RctController@clientexpandreference');

Route::post('/user/rctclient_expand_gross', 'user\RctController@clientexpandgross');

Route::post('/user/rctclient_expand_add_gross', 'user\RctController@clientexpandaddgross');

Route::post('/user/rctclient_expand_add_deduction', 'user\RctController@clientexpandadddeduction');

Route::post('/user/rctclient_expand_check_reference', 'user\RctController@clientexpandcheckreference');



Route::post('/user/rctclient_expand_deduction', 'user\RctController@clientexpanddeduction');

Route::post('/user/rctclient_expand_invoice', 'user\RctController@clientexpandinvoice');

Route::post('/user/rctclient_expand_date_update', 'user\RctController@clientexpanddateupdate');



Route::get('/user/rctclient_expad_delete_item/{id?}', 'user\RctController@clientexpaddeleteitem');

Route::post('/user/rctclient_item_view/{id?}', 'user\RctController@clientitemview');

Route::post('/user/rctclient_item_email/{id?}', 'user\RctController@clientitememail');



Route::post('/user/rctexport_all_pdf/{id?}', 'user\RctController@exportallpdf');

Route::post('/user/rctexport_pdf_rctc/{id?}', 'user\RctController@exportpdfrctc');

Route::post('/user/rctexport_pdf_pctc/{id?}', 'user\RctController@exportpdfpctc');

Route::post('/user/rctexport_pdf_home/{id?}', 'user\RctController@exportpdfhome');



Route::get('/user/rctexport_all_csv/{id?}', 'user\RctController@exportallcsv');

Route::get('/user/rctexport_csv_rctc/{id?}', 'user\RctController@exportcsvrctc');

Route::get('/user/rctexport_csv_pctc/{id?}', 'user\RctController@exportcsvpctc');

Route::get('/user/rctexport_csv_home/{id?}', 'user\RctController@exportcsvhome');

Route::get('/user/rctdownloadpdf', 'user\RctController@downloadpdf');



Route::get('/user/rctclient_search', 'user\RctController@rctclientsearch');

Route::get('/user/rctclient_search_select', 'user\RctController@rctclientsearchselect');

Route::get('/user/rctclient_tax_search', 'user\RctController@clienttaxsearch');

Route::get('/user/rctclient_tax_search_select', 'user\RctController@clienttaxsearchselect');

Route::get('/user/rctclient_email_search', 'user\RctController@clientemailsearch');

Route::get('/user/rctclient_email_search_select', 'user\RctController@clientemailsearchselect');

Route::post('/user/rctclient_email_form', 'user\RctController@clientemailform');

Route::post('/user/rctemail_report_form', 'user\RctController@emailreportform');



Route::get('/user/rctcontractor_search', 'user\RctController@contractorsearch');

Route::get('/user/rctcontractor_search_select', 'user\RctController@contractorsearchselect');



Route::get('/user/rctsub_rct_search', 'user\RctController@subrctsearch');

Route::get('/user/rctsub_rct_select', 'user\RctController@subrctselect');



Route::get('/user/rctreference_search', 'user\RctController@referencesearch');

Route::get('/user/rctreference_select', 'user\RctController@referenceselect');



Route::get('/admin/rctclient_search', 'admin\RctClientsController@rctclientsearch');

Route::get('/admin/rctclient_search_select', 'admin\RctClientsController@rctclientsearchselect');

Route::get('/admin/rctclient_tax_search', 'admin\RctClientsController@clienttaxsearch');

Route::get('/admin/rctclient_tax_search_select', 'admin\RctClientsController@clienttaxsearchselect');

Route::get('/admin/rctclient_email_search', 'admin\RctClientsController@clientemailsearch');

Route::get('/admin/rctclient_email_search_select', 'admin\RctClientsController@clientemailsearchselect');



Route::post('/user/rctimport_form','user\RctController@import_form');

Route::get('/user/rctimport_form_one','user\RctController@import_form_one');

/*----------------------RCT END----------------------*/





/*----------------------CM Stystem START----------------------*/



Route::get('/user/client_management','user\CmController@clientmanagement');

Route::get('/user/clientmanagement_paginate','user\CmController@clientmanagement_paginate');



Route::post('/user/add_cm_clients','user\CmController@addcmclients');

Route::post('/user/edit_cm_client/{id?}', 'user\CmController@editcmclient');

Route::post('/user/copy_cm_client/{id?}', 'user\CmController@copycmclient');

Route::post('/user/cm_status_clients/', 'user\CmController@cm_status_clients');

Route::post('/user/save_image/', 'user\CmController@save_image');

Route::post('/user/cm_print_details/', 'user\CmController@cm_print_details');



Route::post('/user/update_cm_clients/', 'user\CmController@updatecmclients');

Route::get('/user/cm_search_clients','user\CmController@cm_search_clients');

Route::post('/user/update_cm_incomplete_status', 'user\CmController@update_cm_incomplete_status');

Route::post('/user/cm_report_pdf', 'user\CmController@cm_report_pdf');

Route::post('/user/cm_report_pdf_type_2', 'user\CmController@cm_report_pdf_type_2');

Route::post('/user/download_report_pdfs', 'user\CmController@download_report_pdfs');



Route::post('/user/cm_bulkreport_pdf', 'user\CmController@cm_bulkreport_pdf');



Route::post('/user/cm_report_csv', 'user\CmController@cm_report_csv');

Route::post('/user/cm_upload', 'user\CmController@cm_upload');

Route::post('/user/cm_bulk_email', 'user\CmController@cm_bulk_email');

Route::post('/user/email_check_crypt_pin', 'user\CmController@email_check_crypt_pin');

Route::get('/user/get_cm_report_clients', 'user\CmController@get_cm_report_clients');

Route::get('/user/get_cm_bulk_clients', 'user\CmController@get_cm_bulk_clients');

Route::get('/user/get_cm_import_clients', 'user\CmController@get_cm_import_clients');

Route::post('/user/import_new_clients', 'user\CmController@import_new_clients');

Route::get('/user/import_new_clients_one', 'user\CmController@import_new_clients_one');



Route::post('/user/import_existing_clients', 'user\CmController@import_existing_clients');

Route::get('/user/import_existing_clients_one', 'user\CmController@import_existing_clients_one');

Route::post('/user/cm_statement_update', 'user\CmController@cm_statement_update');





Route::post('/user/cm_client_invoice', 'user\CmController@cm_client_invoice');

Route::post('/user/cm_client_payroll', 'user\CmController@cm_client_payroll');



Route::post('/user/cm_invoice_report_csv', 'user\CmController@cm_invoice_report_csv');

Route::post('/user/cm_get_csv_filename', 'user\CmController@cm_get_csv_filename');

Route::post('/user/cm_invoice_report_pdf', 'user\CmController@cm_invoice_report_pdf');

Route::post('/user/cm_invoice_download_report_pdfs', 'user\CmController@cm_invoice_download_report_pdfs');

Route::get('/user/print_selected_invoice', 'user\CmController@print_selected_invoice');





/*----------------------CM Stystem END----------------------*/







Route::get('/user/invoice_management','user\UserController@unavailable');

Route::get('/user/client_statements','user\UserController@unavailable');

Route::get('/user/receipt_management','user\UserController@unavailable');

Route::get('/user/time_management','user\UserController@unavailable');







Route::get('/user/time_me','user\UserController@unavailable');



Route::get('/user/vat_client_search', 'user\UserController@vat_client_search');

Route::get('/user/vat_client_search_select', 'user\UserController@vat_clientsearchselect');



/*----------------------Invoice System START----------------------*/



Route::get('/user/invoice_management','user\InvoiceController@invoicemanagement');

Route::get('/user/invoice_search','user\InvoiceController@invoice_search');

Route::post('/user/show_statement', 'user\InvoiceController@show_statement');

Route::get('/user/invoicemanagement_paginate','user\InvoiceController@invoicemanagement_paginate');

Route::post('/user/report_client_invoice', 'user\InvoiceController@report_client_invoice');

Route::post('/user/invoice_report_csv', 'user\InvoiceController@invoice_report_csv');

Route::post('/user/invoice_report_pdf', 'user\InvoiceController@invoice_report_pdf');

Route::post('/user/invoice_download_report_pdfs', 'user\InvoiceController@invoice_download_report_pdfs');

Route::post('/user/import_new_invoice', 'user\InvoiceController@import_new_invoice');

Route::get('/user/import_new_invoice_one', 'user\InvoiceController@import_new_invoice_one');



Route::post('/user/report_client_invoice_date_filter', 'user\InvoiceController@report_client_invoice_date_filter');

Route::get('/user/invoicemanagement_paginate','user\InvoiceController@invoicemanagement_paginate');

Route::post('/user/invoices_print_view', 'user\InvoiceController@invoicesprintview');

Route::post('/user/invoice_saveas_pdf', 'user\InvoiceController@invoice_saveas_pdf');
Route::post('/user/invoice_print_pdf', 'user\InvoiceController@invoice_print_pdf');






/*----------------------Invoice Stystem END----------------------*/



/*----------------------Time Me Start----------------------*/



Route::get('/user/time_task','user\TimemeController@time_task');

Route::post('/user/time_task_client_details', 'user\TimemeController@time_task_client_details');

Route::post('/user/time_task_add', 'user\TimemeController@time_task_add');

Route::post('/user/time_task_update', 'user\TimemeController@time_task_update');

Route::post('/user/time_task_client_counts', 'user\TimemeController@time_task_client_counts');

Route::post('/user/timetasklock_unlock', 'user\TimemeController@timetasklock_unlock');

Route::post('/user/timetask_edit', 'user\TimemeController@timetask_edit');

Route::post('/user/time_task_review', 'user\TimemeController@time_task_review');

Route::post('/user/time_task_review_all', 'user\TimemeController@time_task_review_all');


/*----------------------Time Me END----------------------*/


/*----------------------Time System Start----------------------*/

Route::get('/user/timesystem_client_search', 'user\TimejobController@timesystem_client_search');
Route::get('/user/timesystem_client_search_select', 'user\TimejobController@timesystem_clientsearchselect');
Route::get('/user/timesystem_client_search_select_tasks', 'user\TimejobController@timesystem_client_search_select_tasks');

Route::post('/user/time_job_add', 'user\TimejobController@timejobadd');
Route::post('/user/time_job_edit', 'user\TimejobController@time_job_edit');

Route::post('/user/time_job_stop', 'user\TimejobController@timejobstop');
Route::post('/user/time_job_stop_quick', 'user\TimejobController@timejobstopquick');

Route::post('/user/edit_time_job_update', 'user\TimejobController@edit_time_job_update');

Route::get('/user/stop_job_details', 'user\TimejobController@stop_job_details');
Route::post('/user/job_add_break', 'user\TimejobController@jobaddbreak');
Route::get('/user/break_time_details', 'user\TimejobController@breaktimedetails');
Route::get('/user/job_user_filter', 'user\TimejobController@jobuserfilter');

Route::get('/user/time_me_overview', 'user\TimejobController@time_active_job');
Route::get('/user/time_me_joboftheday', 'user\TimejobController@time_joboftheday');
Route::get('/user/time_me_client_review', 'user\TimejobController@time_client_review');
Route::get('/user/time_me_all_job', 'user\TimejobController@time_all_job');
Route::get('/user/staff_review', 'user\TimejobController@staff_review');
Route::get('/user/search_staff_review', 'user\TimejobController@search_staff_review');
Route::get('/user/staff_review_download_as_pdf', 'user\TimejobController@staff_review_download_as_pdf');



Route::post('/user/job_time_count_refresh', 'user\TimejobController@jobtimecountrefresh');

Route::post('/user/active_job_report_csv', 'user\TimejobController@active_job_report_csv');
Route::post('/user/active_job_report_pdf', 'user\TimejobController@active_jobreportpdf');
Route::post('/user/active_job_report_pdf_download', 'user\TimejobController@active_jobreportpdfdownload');

Route::post('/user/all_job_report_csv', 'user\TimejobController@all_job_report_csv');
Route::post('/user/all_job_report_pdf', 'user\TimejobController@all_jobreportpdf');
Route::post('/user/all_job_report_pdf_download', 'user\TimejobController@all_jobreportpdfdownload');


Route::post('/user/joboftheday_report_csv', 'user\TimejobController@joboftheday_report_csv');
Route::post('/user/joboftheday_report_pdf', 'user\TimejobController@joboftheday_reportpdf');
Route::post('/user/joboftheday_report_pdf_download', 'user\TimejobController@joboftheday_report_pdf_download');

Route::post('/user/clientreview_report_csv', 'user\TimejobController@clientreview_report_csv');
Route::post('/user/clientreview_report_pdf', 'user\TimejobController@clientreview_report_pdf');
Route::post('/user/clientreview_report_pdf_download', 'user\TimejobController@clientreview_report_pdf_download');



Route::get('/user/search_job_of_day', 'user\TimejobController@searchjobofday');
Route::get('/user/search_client_review', 'user\TimejobController@search_client_review');

Route::get('/user/get_job_details', 'user\TimejobController@get_job_details');
Route::post('/user/check_time_me_user_active_job', 'user\TimejobController@check_time_me_user_active_job');
Route::post('/user/check_last_finished_job_time', 'user\TimejobController@check_last_finished_job_time');







/*----------------------Time System END----------------------*/




Route::get('/user/dashboard', 'user\UserController@dashboard');

Route::get('/user/task_client_search', 'user\UserController@task_client_search');

Route::get('/user/task_client_search_select', 'user\UserController@task_clientsearchselect');




/*----------------------CM START----------------------*/

Route::get('/admin/cm_profile', 'admin\CmsystemController@cmprofile');

Route::post('/admin/update_cm_crypt', 'admin\CmsystemController@updatecmcrypt');





Route::get('/admin/cm_clients_list', 'admin\CmsystemController@cm_clients_list');

Route::get('/admin/manage_cm_class', 'admin\CmsystemController@cmclass');

Route::post('/admin/add_cm_class', 'admin\CmsystemController@addclass');

Route::post('/admin/edit_cm_class/{id?}', 'admin\CmsystemController@editcmclass');

Route::post('/admin/update_cm_class/', 'admin\CmsystemController@updatecmclass');

Route::get('/admin/deactive_cm_class/{id?}', 'admin\CmsystemController@deactivecmclass');

Route::get('/admin/active_cm_class/{id?}', 'admin\CmsystemController@activecmclass');



Route::get('/admin/manage_cm_paper', 'admin\CmsystemController@cmpaper');

Route::post('/admin/add_cm_paper', 'admin\CmsystemController@addpaper');

Route::post('/admin/edit_cm_paper/{id?}', 'admin\CmsystemController@editcmpaper');

Route::post('/admin/update_cm_paper/', 'admin\CmsystemController@updatecmpaper');

Route::get('/admin/deactive_cm_paper/{id?}', 'admin\CmsystemController@deactivecmpaper');

Route::get('/admin/active_cm_paper/{id?}', 'admin\CmsystemController@activecmpaper');



Route::get('/admin/manage_cm_fields', 'admin\CmsystemController@cmfields');

Route::post('/admin/add_cm_field', 'admin\CmsystemController@addfield');

Route::post('/admin/edit_cm_field/{id?}', 'admin\CmsystemController@editfield');

Route::post('/admin/update_cm_field/', 'admin\CmsystemController@updatecmfield');

Route::get('/admin/deactive_cm_field/{id?}', 'admin\CmsystemController@deactivefield');

Route::get('/admin/active_cm_field/{id?}', 'admin\CmsystemController@activefield');

Route::get('/admin/cm_client_checkfield/', 'admin\CmsystemController@cm_client_checkfield');

Route::get('/admin/cm_search_clients','admin\CmsystemController@cm_search_clients');

Route::post('/admin/update_cm_incomplete_status', 'admin\CmsystemController@update_cm_incomplete_status');

Route::post('/admin/change_cm_client_class', 'admin\CmsystemController@change_cm_client_class');



/*----------------------CM END----------------------*/

Route::get('/user/resendedit_email_unsent_files', 'user\UserController@resendedit_email_unsent_files');
Route::get('/user/task_complete_update/', 'user\UserController@task_complete_update');

Route::get('/user/time_track', 'user\UserController@time_track');
Route::get('/user/get_quick_break_details', 'user\TimejobController@get_quick_break_details');
Route::get('/user/calculate_job_time', 'user\TimejobController@calculate_job_time');
Route::get('/user/calculate_break_time', 'user\TimejobController@calculate_break_time');


Route::get('/user/in_file/', 'user\InfileController@infile');
Route::get('/user/in_file_advance/', 'user\InfileController@infile_advance');

Route::get('/user/infile_user_update/', 'user\InfileController@infile_userupdate');
Route::get('/user/infile_complete_date/', 'user\InfileController@infile_completedate');
Route::get('/user/in_file_status_update/', 'user\InfileController@in_file_statusupdate');
Route::post('/user/in_file_show_incomplete/', 'user\InfileController@in_file_showincomplete');

Route::get('/user/infile_client_search', 'user\InfileController@infile_client_search');
Route::get('/user/infile_client_search_select', 'user\InfileController@infile_clientsearchselect');
Route::post('/user/infile_image_upload', 'user\InfileController@infile_imageupload');
Route::post('/user/infile_upload_images', 'user\InfileController@infile_upload_images');
Route::post('/user/infile_remove_dropzone_attachment', 'user\InfileController@remove_dropzone_attachment');
Route::get('/user/infile_delete_image', 'user\InfileController@infile_delete_image');
Route::get('/user/infile_delete_all_image', 'user\InfileController@infile_delete_all_image');

Route::get('/user/infile_download_image', 'user\InfileController@infile_download_image');
Route::get('/user/infile_download_all_image', 'user\InfileController@infile_download_all_image');
Route::get('/user/infile_download_rename_all_image', 'user\InfileController@infile_download_rename_all_image');
Route::get('/user/infile_download_bpso_all_image', 'user\InfileController@infile_download_bpso_all_image');



Route::post('/user/infile_notepad_upload', 'user\InfileController@infile_notepad_upload');
Route::post('/user/infile_notepad_upload_notes', 'user\InfileController@infile_notepad_upload_notes');
Route::get('/user/infile_delete_all_notes_only', 'user\InfileController@infile_delete_all_notes_only');

Route::get('/user/infile_delete_all_notes', 'user\InfileController@infile_delete_all_notes');

Route::get('/user/infile_download_all_notes_only', 'user\InfileController@infile_download_all_notes_only');
Route::get('/user/infile_download_all_notes', 'user\InfileController@infile_download_all_notes');

Route::get('/user/task_client_common_search', 'user\InfileController@infile_commonclient_search');
Route::get('/user/task_client_common_search_select', 'user\InfileController@infile_commonclientsearchselect');
Route::post('/user/add_notepad_contents', 'user\InfileController@add_notepad_contents');
Route::post('/user/infile_upload_images_add', 'user\InfileController@infile_upload_images_add');
Route::post('/user/create_new_file', 'user\InfileController@create_new_file');\
Route::post('/user/clear_session_attachments', 'user\InfileController@clear_session_attachments');

Route::post('/user/delete_file_link', 'user\InfileController@delete_file_link');
Route::get('/user/infile_search', 'user\InfileController@infile_search');
Route::get('/user/infile_internal', 'user\InfileController@infile_internal');

Route::post('/user/fileattachment_status', 'user\InfileController@fileattachment_status');
Route::get('/user/infile_email_notify_tasks_pdf', 'user\InfileController@infile_email_notify_tasks_pdf');
Route::get('/user/change_attachment_text_status', 'user\InfileController@change_attachment_text_status');
Route::get('/user/remove_attachment_text_status', 'user\InfileController@remove_attachment_text_status');
Route::get('/user/update_fileattachment_textval', 'user\InfileController@update_fileattachment_textval');
Route::get('/user/get_attachment_details', 'user\InfileController@get_attachment_details');

Route::get('/user/infile_task_client_search', 'user\InfileController@infile_task_client_search');

Route::get('/user/email_report_generator', 'user\UserController@email_report_generator');

Route::post('/user/report_infile', 'user\InfileController@report_infile');
Route::post('/user/infile_report_pdf', 'user\InfileController@infile_report_pdf');
Route::post('/user/download_infile_report_pdf', 'user\InfileController@download_infile_report_pdf');
Route::post('/user/infile_report_csv', 'user\InfileController@infile_report_csv');
Route::post('/user/infile_report_csv_single', 'user\InfileController@infile_report_csv_single');

Route::post('/user/infile_report_pdf_single', 'user\InfileController@infile_report_pdf_single');
Route::post('/user/download_infile_report_pdf_single', 'user\InfileController@download_infile_report_pdf_single');

Route::post('/user/infile_report_incomplete', 'user\InfileController@infile_report_incomplete');
Route::post('/user/change_attachment_bpso_status', 'user\InfileController@change_attachment_bpso_status');
Route::post('/user/infile_incomplete_status', 'user\InfileController@infile_incomplete_status');


Route::get('/user/donot_complete_task_details/', 'user\UserController@donot_complete_task_details');
Route::get('/user/task_complete_update_new/', 'user\UserController@task_complete_update_new');

Route::get('/user/edit_time_job', 'user\TimejobController@edit_time_job');

Route::post('/user/cm_note_update','user\CmController@cm_note_update');

Route::get('/user/task_default_users_update', 'user\UserController@task_default_users_update');




/*----------------------Supplementary Start----------------------*/

Route::get('/user/supplementary_manager', 'user\SupplementaryController@supplementary_manager');
Route::post('/user/supplementary_add', 'user\SupplementaryController@supplementary_add');
Route::get('/user/supple_number_check', 'user\SupplementaryController@supple_number_check');
Route::get('/user/supplementary_note_create/{id}', 'user\SupplementaryController@supplementary_note_create');
Route::post('/user/supple_value_update', 'user\SupplementaryController@supple_value_update');
Route::post('/user/supple_type_update', 'user\SupplementaryController@supple_type_update');



/*----------------------Supplementary End----------------------*/

/*----------------------yearend Start----------------------*/

Route::get('/user/year_end_manager','user\YearendController@YearendController');
Route::post('/user/yearend_crypt_validdation','user\YearendController@yearend_crypt_validdation');
Route::post('/user/year_first_create', 'user\YearendController@year_first_create');
Route::get('/user/yearend_setting', 'user\YearendController@yearend_setting');
Route::post('/user/year_setting_create', 'user\YearendController@year_setting_create');
Route::post('/user/active_checkbox', 'user\YearendController@active_checkbox');
Route::post('/user/year_setting_edit', 'user\YearendController@year_setting_edit');
Route::post('/user/yearend_crypt_setting_add','user\YearendController@yearend_crypt_setting_add');
Route::post('/user/year_setting_update', 'user\YearendController@year_setting_update');
Route::get('/user/yeadend_clients/{id}', 'user\YearendController@yeadend_clients');
Route::get('/user/yearend_individualclient/{id}', 'user\YearendController@yearend_individualclient');
Route::post('/user/year_setting_copy_to_year/', 'user\YearendController@year_setting_copy_to_year');

Route::post('/user/dist_emailupdate', 'user\YearendController@dist_emailupdate');




/*************************************New Added Year End Setting*****************************************/

Route::post('/user/yearend_upload_images_add', 'user\YearendController@yearend_upload_images_add');
Route::post('/user/yearend_clear_session_attachments', 'user\YearendController@yearend_clear_session_attachments');
Route::post('/user/remove_all_attachments', 'user\YearendController@remove_all_attachments');

/*************************************Newly Added on 13-12-2018*****************************************/

Route::post('/user/yearend_upload_images_edit', 'user\YearendController@yearend_upload_images_edit');
Route::post('/user/remove_year_setting_attachment', 'user\YearendController@remove_year_setting_attachment');

/*----------------------Yearend Start----------------------*/


/*----------------------Supplementary 20-12-2018 Start----------------------*/

Route::post('/user/supple_comboone_update', 'user\SupplementaryController@supple_comboone_update');
Route::post('/user/supple_formula_update', 'user\SupplementaryController@supple_formula_update');
Route::post('/user/supple_combotwo_update', 'user\SupplementaryController@supple_combotwo_update');
Route::post('/user/supplementary_load', 'user\SupplementaryController@supplementary_load');

/*----------------------Supplementary 20-12-2018 End----------------------*/

/*----------------------Year End System 25-12-2018 Start----------------------*/
Route::post('/user/yearend_individual_attachment', 'user\YearendController@yearend_individual_attachment');
Route::post('/user/yearend_attachment_individual', 'user\YearendController@yearend_attachment_individual');
Route::post('/user/yearend_delete_image', 'user\YearendController@yearend_delete_image');
Route::post('/user/yearend_delete_all_image', 'user\YearendController@yearend_delete_all_image');
Route::post('/user/remove_yearend_dropzone_attachment', 'user\YearendController@remove_yearend_dropzone_attachment');

Route::post('/user/distribution_future', 'user\YearendController@distribution_future');
Route::post('/user/distribution1_future', 'user\YearendController@distribution1_future');
Route::post('/user/distribution2_future', 'user\YearendController@distribution2_future');
Route::post('/user/distribution3_future', 'user\YearendController@distribution3_future');
Route::post('/user/setting_active_update', 'user\YearendController@setting_active_update');
Route::get('/user/check_already_attached', 'user\YearendController@check_already_attached');
Route::post('/user/insert_notes_yearend', 'user\YearendController@insert_notes_yearend');
Route::post('/user/yearend_delete_note', 'user\YearendController@yearend_delete_note');
Route::post('/user/yearend_delete_all_note', 'user\YearendController@yearend_delete_all_note');

Route::get('/user/yearend_create_new_year', 'user\YearendController@yearend_create_new_year');
Route::get('/user/review_get_clients', 'user\YearendController@review_get_clients');
Route::post('/user/review_clients_update', 'user\YearendController@review_clients_update');
Route::post('/user/download_email_format', 'user\YearendController@download_email_format');

Route::get('/user/edit_yearend_email_unsent_files', 'user\YearendController@edit_yearend_email_unsent_files');
Route::post('/user/yearend_email_unsent_files', 'user\YearendController@yearend_email_unsent_files');

Route::post('/user/make_client_disable', 'user\YearendController@make_client_disable');
Route::post('/user/select_template', 'user\YearendController@select_template');
Route::post('/user/save_user_note', 'user\YearendController@save_user_note');
Route::post('/user/set_client_year_end_date', 'user\YearendController@set_client_year_end_date');
Route::post('/user/update_na_status', 'user\YearendController@update_na_status');

Route::post('/user/update_fixed_text', 'user\SupplementaryController@update_fixed_text');
Route::post('/user/save_supplementary_note', 'user\SupplementaryController@save_supplementary_note');
Route::post('/user/update_supplementary_note', 'user\SupplementaryController@update_supplementary_note');

Route::get('/user/edit_supplementary_note/{id}', 'user\SupplementaryController@edit_supplementary_note');

Route::post('/user/supple_comboone_update_edit', 'user\SupplementaryController@supple_comboone_update_edit');
Route::post('/user/supple_formula_update_edit', 'user\SupplementaryController@supple_formula_update_edit');
Route::post('/user/supple_combotwo_update_edit', 'user\SupplementaryController@supple_combotwo_update_edit');
Route::post('/user/update_fixed_text_edit', 'user\SupplementaryController@update_fixed_text_edit');
Route::post('/user/supple_type_update_edit', 'user\SupplementaryController@supple_type_update_edit');
Route::post('/user/supple_value_update_edit', 'user\SupplementaryController@supple_value_update_edit');

Route::get('/user/delete_supplementary_note/{id}', 'user\SupplementaryController@delete_supplementary_note');
Route::get('/user/download_supplementary_note', 'user\SupplementaryController@download_supplementary_note');


/*----------------------Paye M.R.S P30 02-03-2019 Start----------------------*/
Route::post('/user/update_paye_p30_first_year', 'user\Payep30Controller@update_paye_p30_first_year');
/*Route::get('/user/paye_p30month_manage/{id?}', 'user\Payep30Controller@paye_p30month_manage');
Route::get('/user/paye_p30_select_month/{id?}', 'user\Payep30Controller@paye_p30_select_month');
Route::get('/user/paye_p30_review_month/{id?}', 'user\Payep30Controller@paye_p30_review_month');

Route::post('/user/update_paye_p30_task_status', 'user\Payep30Controller@update_paye_p30_task_status');
Route::post('/user/update_paye_p30_hide_task_status', 'user\Payep30Controller@update_paye_p30_hide_task_status');

Route::post('/user/update_paye_p30_hide_columns_status', 'user\Payep30Controller@update_paye_p30_hide_columns_status');
Route::post('/user/update_paye_p30_columns_status', 'user\Payep30Controller@update_paye_p30_columns_status');
Route::post('/user/update_paye_p30_columns_status_selectall', 'user\Payep30Controller@update_paye_p30_columns_status_selectall');
Route::get('/user/paye_p30_ros_liability_update', 'user\Payep30Controller@paye_p30_ros_liability_update');
Route::get('/user/refresh_paye_p30_liability', 'user\Payep30Controller@refresh_paye_p30_liability');

Route::get('/user/paye_p30_edit_email_unsent_files', 'user\Payep30Controller@paye_p30_edit_email_unsent_files');
Route::post('/user/paye_p30_email_unsent_files', 'user\Payep30Controller@paye_p30_email_unsent_files');*/


/*----------------------GBS Paye M.R.S P30 02-03-2019 Start----------------------*/
Route::post('/user/gbs_update_paye_p30_first_year', 'user\Gbspayep30Controller@gbs_update_paye_p30_first_year');
Route::get('/user/gbs_paye_p30month_manage/{id?}', 'user\Gbspayep30Controller@gbs_paye_p30month_manage');
Route::get('/user/gbs_paye_p30_select_month/{id?}', 'user\Gbspayep30Controller@gbs_paye_p30_select_month');
Route::get('/user/gbs_paye_p30_review_month/{id?}', 'user\Gbspayep30Controller@gbs_paye_p30_review_month');

Route::post('/user/gbs_update_paye_p30_task_status', 'user\Gbspayep30Controller@gbs_update_paye_p30_task_status');
Route::post('/user/gbs_update_paye_p30_hide_task_status', 'user\Gbspayep30Controller@gbs_update_paye_p30_hide_task_status');

Route::post('/user/gbs_update_paye_p30_hide_columns_status', 'user\Gbspayep30Controller@gbs_update_paye_p30_hide_columns_status');
Route::post('/user/gbs_update_paye_p30_columns_status', 'user\Gbspayep30Controller@gbs_update_paye_p30_columns_status');
Route::post('/user/gbs_update_paye_p30_columns_status_selectall', 'user\Gbspayep30Controller@gbs_update_paye_p30_columns_status_selectall');
Route::get('/user/gbs_paye_p30_ros_liability_update', 'user\Gbspayep30Controller@gbs_paye_p30_ros_liability_update');
Route::get('/user/gbs_refresh_paye_p30_liability', 'user\Gbspayep30Controller@gbs_refresh_paye_p30_liability');

Route::get('/user/gbs_paye_p30_edit_email_unsent_files', 'user\Gbspayep30Controller@gbs_paye_p30_edit_email_unsent_files');
Route::post('/user/gbs_paye_p30_email_unsent_files', 'user\Gbspayep30Controller@gbs_paye_p30_email_unsent_files');

/*----------------------GBS Paye M.R.S P30 02-03-2019 Start----------------------*/

Route::get('/user/paye_p30_manage/{id?}', 'user\Payep30Controller@paye_p30_manage');
Route::get('/user/paye_p30_review_year/{id?}', 'user\Payep30Controller@paye_p30_review_year');
Route::post('/user/paye_p30_periods_update/', 'user\Payep30Controller@paye_p30_periods_update');
Route::get('/user/refresh_paye_p30_liability', 'user\Payep30Controller@refresh_paye_p30_liability');
Route::post('/user/paye_p30_periods_remove', 'user\Payep30Controller@paye_p30_periods_remove');
Route::post('/user/paye_p30_ros_update', 'user\Payep30Controller@paye_p30_ros_update');
Route::post('/user/paye_p30_apply', 'user\Payep30Controller@paye_p30_apply');
Route::post('/user/paye_p30_single_month', 'user\Payep30Controller@paye_p30_single_month');
Route::post('/user/paye_p30_all_month', 'user\Payep30Controller@paye_p30_all_month');


Route::post('/user/paye_p30_periods_month_update/', 'user\Payep30Controller@paye_p30_periods_month_update');
Route::post('/user/paye_p30_periods_month_remove', 'user\Payep30Controller@paye_p30_periods_month_remove');
Route::post('/user/paye_p30_active_periods', 'user\Payep30Controller@paye_p30_active_periods');
Route::post('/user/paye_p30_all_periods', 'user\Payep30Controller@paye_p30_all_periods');
Route::get('/user/paye_p30_edit_email_unsent_files', 'user\Payep30Controller@paye_p30_edit_email_unsent_files');
Route::post('/user/paye_p30_email_unsent_files', 'user\Payep30Controller@paye_p30_email_unsent_files');


Route::post('/user/load_table_info', 'user\Payep30Controller@load_table_info');
Route::get('/user/paye_p30_create_new_year', 'user\Payep30Controller@paye_p30_create_new_year');

Route::post('/user/paye_p30_week_selected', 'user\Payep30Controller@paye_p30_week_selected');
Route::post('/user/paye_p30_month_selected', 'user\Payep30Controller@paye_p30_month_selected');
Route::post('/user/update_paye_p30_clients_status', 'user\Payep30Controller@update_paye_p30_clients_status');
Route::post('/user/update_paye_p30_year_email_clients_status', 'user\Payep30Controller@update_paye_p30_year_email_clients_status');
Route::post('/user/update_paye_p30_year_disabled_status', 'user\Payep30Controller@update_paye_p30_year_disabled_status');
Route::post('/user/paye_p30_create_csv', 'user\Payep30Controller@paye_p30_create_csv');
Route::get('/user/check_paye_task_details', 'user\Payep30Controller@check_paye_task_details');
Route::get('/user/update_paye_task_details', 'user\Payep30Controller@update_paye_task_details');
Route::post('/user/payments_attachment', 'user\Payep30Controller@payments_attachment');


/*----------------------GBS Paye M.R.S P30 10-09-2019 Start----------------------*/
Route::post('/user/paye_p30_payment_update', 'user\Payep30Controller@paye_p30_payment_update');

/*----------------------Infile 10-09-2019 Start----------------------*/
Route::post('/user/bpso_all_check', 'user\InfileController@bpso_all_check');

/*----------------------AML System 10-09-2019 Start----------------------*/
Route::get('/user/aml_system','user\AmlController@aml_system');
Route::post('/user/update_aml_incomplete_status', 'user\AmlController@update_aml_incomplete_status');
Route::post('/user/aml_system_client_source_refresh', 'user\AmlController@aml_system_client_source_refresh');
Route::post('/user/aml_system_risk_update', 'user\AmlController@aml_system_risk_update');

Route::get('/user/aml_client_search', 'user\AmlController@aml_client_search');
Route::get('/user/aml_client_search_select', 'user\AmlController@aml_clientsearchselect');

Route::post('/user/aml_system_other_client', 'user\AmlController@aml_system_other_client');
Route::post('/user/aml_system_partner', 'user\AmlController@aml_system_partner');
Route::post('/user/aml_system_note', 'user\AmlController@aml_system_note');
Route::post('/user/aml_system_add_bank', 'user\AmlController@aml_system_add_bank');
Route::post('/user/aml_system_bank_details', 'user\AmlController@aml_system_bank_details');

Route::post('/user/cm_client_add_bank', 'user\CmController@cm_client_add_bank');

Route::post('/user/aml_system_review', 'user\AmlController@aml_system_review');
Route::post('/user/aml_system_review_edit', 'user\AmlController@aml_system_review_edit');
Route::post('/user/aml_system_review_edit_update', 'user\AmlController@aml_system_review_edit_update');
Route::post('/user/aml_system_review_delete', 'user\AmlController@aml_system_review_delete');
Route::post('/user/aml_upload_images_add', 'user\AmlController@aml_upload_images_add');
Route::post('/user/aml_system_image_upload', 'user\AmlController@aml_system_image_upload');
Route::post('/user/aml_system_delete_attached', 'user\AmlController@aml_system_delete_attached');

Route::post('/user/aml_system_client_since', 'user\AmlController@aml_system_client_since');
Route::post('/user/aml_report_pdf', 'user\AmlController@aml_report_pdf');
Route::post('/user/aml_download_report_pdfs', 'user\AmlController@aml_download_report_pdfs');
Route::post('/user/aml_remove_dropzone_attachment', 'user\AmlController@aml_remove_dropzone_attachment');
Route::get('/user/notify_tasks_aml', 'user\AmlController@notify_tasks_aml');
Route::get('/user/email_notify_aml', 'user\AmlController@email_notify_aml');
Route::get('/user/aml_edit_email_unsent_files', 'user\AmlController@aml_edit_email_unsent_files');
Route::post('/user/aml_email_unsent_files', 'user\AmlController@aml_email_unsent_files');
Route::get('/user/standard_file_name', 'user\AmlController@standard_file_name');
Route::get('/user/generate_aml_text_file', 'user\AmlController@generate_aml_text_file');
Route::post('/user/aml_system_add_trade', 'user\AmlController@aml_system_add_trade');
Route::get('/user/get_trade_details', 'user\AmlController@get_trade_details');


/*----------------------Client Request Manager Start----------------------*/
Route::get('/admin/setup_request_category', 'admin\RequestController@setuprequestcategory');
Route::get('/admin/deactive_request/{id?}', 'admin\RequestController@deactiverequest');
Route::get('/admin/active_request/{id?}', 'admin\RequestController@activerequest');
Route::get('/admin/delete_request/{id?}', 'admin\RequestController@deleterequest');
Route::post('/admin/request_signature', 'admin\RequestController@requestsignature');
Route::post('/admin/request_add', 'admin\RequestController@requestadd');
Route::get('/admin/request_edit_category', 'admin\RequestController@request_edit_category');
Route::post('/admin/request_edit_form', 'admin\RequestController@request_edit_form');

Route::get('/user/client_request_system', 'user\CrmController@clientrequestsystem');
Route::get('/user/client_request_manager/{id?}', 'user\CrmController@clientrequestmanager');
Route::get('/user/client_request_edit/{id?}', 'user\CrmController@client_requestedit');

Route::post('/user/client_request_modal', 'user\CrmController@clientrequestmodal');
Route::post('/user/request_add_bank_statement', 'user\CrmController@requestaddbankstatement');	
Route::get('/user/request_delete_statement/{id?}', 'user\CrmController@requestdeletestatement');

Route::post('/user/request_add_others', 'user\CrmController@requestaddothers');	
Route::get('/user/request_delete_other/{id?}', 'user\CrmController@requestdeleteother');

Route::post('/user/request_add_cheque', 'user\CrmController@requestaddcheque');	
Route::get('/user/request_delete_cheque/{id?}', 'user\CrmController@requestdeletecheque');
Route::get('/user/request_bank_received/{id?}', 'user\CrmController@requestbankreceived');
Route::get('/user/request_cheque_received/{id?}', 'user\CrmController@requestchequereceived');
Route::get('/user/request_cheque_notreceived/{id?}', 'user\CrmController@requestchequenotreceived');
Route::get('/user/request_other_received/{id?}', 'user\CrmController@requestotherreceived');
Route::get('/user/request_other_notreceived/{id?}', 'user\CrmController@requestothernotreceived');

Route::post('/user/request_purchase_invoice_add', 'user\CrmController@requestpurchaseinvoiceadd');

Route::get('/user/request_purchase_received/{id?}', 'user\CrmController@requestpurchasereceived');
Route::get('/user/request_purchase_notreceived/{id?}', 'user\CrmController@requestpurchasenotreceived');
Route::get('/user/request_delete_purchase/{id?}', 'user\CrmController@requestdeletepurchase');

Route::get('/user/request_delete_purchase_attach/{id?}', 'user\CrmController@requestdeletepurchaseattach');
Route::get('/user/request_delete_cheque_attach/{id?}', 'user\CrmController@requestdeletechequeattach');

Route::get('/user/request_sales_received_attach/{id?}', 'user\CrmController@requestsalesreceivedattach');
Route::get('/user/request_sales_notreceived_attach/{id?}', 'user\CrmController@requestsalesnotreceivedattach');
Route::get('/user/request_bank_statement/{id?}', 'user\CrmController@requestbankstatement');
Route::get('/user/request_bank_statement_notreceived/{id?}', 'user\CrmController@requestbankstatementnotreceived');



Route::get('/user/request_cheque_received_attach/{id?}', 'user\CrmController@requestchequereceivedattach');
Route::get('/user/request_cheque_notreceived_attach/{id?}', 'user\CrmController@requestchequenotreceivedattach');
Route::get('/user/request_purchase_received_attach/{id?}', 'user\CrmController@requestpurchasereceivedattach');
Route::get('/user/request_purchase_notreceived_attach/{id?}', 'user\CrmController@requestpurchasenotreceivedattach');

Route::post('/user/request_add_sales', 'user\CrmController@requestaddsales');

Route::get('/user/request_sales_received/{id?}', 'user\CrmController@requestsalesreceived');
Route::get('/user/request_sales_notreceived/{id?}', 'user\CrmController@requestsalesnotreceived');
Route::get('/user/request_delete_sales/{id?}', 'user\CrmController@requestdeletesales');

Route::get('/user/request_delete_sales_attach/{id?}', 'user\CrmController@requestdeletesalesattach');
Route::post('/user/client_request_year_category_user/', 'user\CrmController@clientrequestyearcategoryuser');
Route::get('/user/request_received_all/{id?}', 'user\CrmController@requestreceivedall');
Route::get('/user/request_new_add/{id?}', 'user\CrmController@requestnewadd');

Route::get('/user/request_delete/{id?}', 'user\CrmController@requestdelete');


Route::post('/user/client_request_view/', 'user\CrmController@client_requestview');
Route::post('/user/download_request_view/', 'user\CrmController@download_request_view');

Route::post('/user/crm_upload_images_purchase', 'user\CrmController@crm_upload_images_purchase');
Route::post('/user/crm_upload_images_sales', 'user\CrmController@crm_upload_images_sales');
Route::post('/user/crm_upload_images_cheque', 'user\CrmController@crm_upload_images_cheque');

Route::post('/user/clear_session_attachments_purchase', 'user\CrmController@clear_session_attachments_purchase');
Route::post('/user/clear_session_attachments_sales', 'user\CrmController@clear_session_attachments_sales');
Route::post('/user/clear_session_attachments_cheque', 'user\CrmController@clear_session_attachments_cheque');
Route::post('/user/send_request_for_approval_edit', 'user\CrmController@send_request_for_approval_edit');
Route::post('/user/send_request_to_client_edit', 'user\CrmController@send_request_to_client_edit');
Route::post('/user/send_request_to_client_edit_none_received', 'user\CrmController@send_request_to_client_edit_none_received');
Route::post('/user/send_request_to_client_some_not_edit', 'user\CrmController@send_request_to_client_some_not_edit');

Route::post('/user/email_to_client', 'user\CrmController@email_to_client');
Route::post('/user/email_for_approval', 'user\CrmController@email_for_approval');

/*client request Manager
0 = awaiting
1 = Email Sent

folder path = uploads/crm/client_id/request_id/cheque_book/files.jpg
folder path = uploads/crm/client_id/request_id/purchase_invcoice/files.jpg
folder path = uploads/crm/client_id/request_id/sales_invcoice/files.jpg*/

/*----admin Staff costing*/

Route::post('/admin/manage_user_costing', 'admin\UserController@manageusercosting');
Route::post('/admin/user_costing_update', 'admin\UserController@usercostingupdate');
Route::post('/admin/manage_user_cost_add', 'admin\UserController@manageusercostadd');
Route::post('/admin/manage_user_costing_delete', 'admin\UserController@manageusercostingdelete');


/*----user year end liability------*/


Route::post('/user/yearend_liability_update', 'user\YearendController@yearendliabilityupdate');
Route::get('/user/yeadend_liability/{id?}', 'user\YearendController@yeadendliability');
Route::post('/user/yearend_liability_setting_result', 'user\YearendController@yearendliabilitysettingresult');
Route::post('/user/yearend_liability_payment', 'user\YearendController@yearendliabilitypayment');
Route::post('/user/yearend_liability_prelim', 'user\YearendController@yearendliabilityprelim');
Route::post('/user/yearend_liability_export', 'user\YearendController@yearendliabilityexport');

/*----USER TA System------*/
Route::get('/user/ta_system', 'user\TaController@ta_system');
Route::post('/user/ta_system_ajax_response', 'user\TaController@ta_system_ajax_response');
Route::get('/user/ta_allocation', 'user\TaController@taallocation');
Route::get('/user/ta_overview', 'user\TaController@taoverview');
Route::get('/user/ta_auto_allocation', 'user\TaController@taautoallocation');


Route::get('/user/ta_allocation_client_search', 'user\TaController@ta_allocation_client_search');
Route::get('/user/ta_allocation_client_search_result', 'user\TaController@ta_allocation_client_search_result');
Route::post('/user/ta_invoice_update', 'user\TaController@tainvoiceupdate');
Route::post('/user/ta_tasks_update', 'user\TaController@tatasksupdate');
Route::post('/user/ta_tasks_update_unallocate', 'user\TaController@tatasksupdateunallocate');


Route::get('/user/ta_overview_client_search', 'user\TaController@ta_overview_client_search');
Route::get('/user/ta_overview_client_search_result', 'user\TaController@ta_overview_client_search_result');

Route::post('/user/ta_overview_invoice', 'user\TaController@taoverviewinvoice');

Route::get('/user/ta_autoalloaction_client_search', 'user\TaController@ta_autoalloaction_client_search');
Route::get('/user/ta_autoalloaction_client_search_result', 'user\TaController@ta_autoalloaction_client_search_result');

Route::post('/user/ta_auto_allocation_invoice', 'user\TaController@taautoallocationinvoice');
Route::post('/user/ta_auto_allocation_tasks', 'user\TaController@taautoallocationtasks');
Route::post('/user/ta_auto_allocation_tasks_yes', 'user\TaController@taautoallocationtasks_yes');
Route::post('/user/ta_auto_allocation_tasks_yes_individual', 'user\TaController@taautoallocationtasks_yes_individual');

Route::post('/user/ta_auto_unallocation_tasks', 'user\TaController@taautounallocationtasks');
Route::post('/user/ta_excluded', 'user\TaController@taexcluded');
Route::post('/user/ta_include', 'user\TaController@tainclude');

Route::post('/user/download_csv_allocated_tasks', 'user\TaController@download_csv_allocated_tasks');
Route::post('/user/download_csv_allocated_invoices', 'user\TaController@download_csv_allocated_invoices');
Route::post('/user/download_csv_active_invoices', 'user\TaController@download_csv_active_invoices');
Route::post('/user/download_csv_task_summary', 'user\TaController@download_csv_task_summary');


/*----USER RCT System New------*/

Route::get('/user/rct_system', 'user\RctControllerNew@rctsystem');
Route::get('/user/rct_client_manager/{id?}', 'user\RctControllerNew@rctclientmanager');
Route::post('/user/rct_add_tax/', 'user\RctControllerNew@rctaddtax');
Route::get('/user/rct_tax_number_check', 'user\RctControllerNew@rcttaxnumbercheck');
Route::post('/user/rct_add_submission/', 'user\RctControllerNew@rctaddsubmission');
Route::get('/user/rct_submission_check', 'user\RctControllerNew@rctsubmissioncheck');
Route::post('/user/rct_delete_submission/', 'user\RctControllerNew@rctdeletesubmission');
Route::post('/user/rct_edit_submission/', 'user\RctControllerNew@rcteditsubmission');

Route::post('/user/rct_edit_submission_update/', 'user\RctControllerNew@rcteditsubmissionupdate');
Route::post('/user/rct_saveaspdf/', 'user\RctControllerNew@rctsaveaspdf');
Route::post('/user/save_as_rct_pdf_download/', 'user\RctControllerNew@save_as_rct_pdf_download');

Route::get('/user/rct_liability_assessment/{id?}', 'user\RctControllerNew@rctliabilityassessment');
Route::post('/user/rct_submission_view/', 'user\RctControllerNew@rctsubmissionview');

Route::post('/user/rct_liability_filter/', 'user\RctControllerNew@rctliabilityfilter');


Route::post('/user/set_rct_active_month/', 'user\RctControllerNew@set_rct_active_month');
Route::post('/user/set_rct_active_month_individual/', 'user\RctControllerNew@set_rct_active_month_individual');
Route::post('/user/rct_load_all_liabilities/', 'user\RctControllerNew@rct_load_all_liabilities');
Route::post('/user/rct_extract_csv_liabilities/', 'user\RctControllerNew@rct_extract_csv_liabilities');
Route::post('/user/rct_rebuild_all_liabilities/', 'user\RctControllerNew@rct_rebuild_all_liabilities');
Route::post('/user/rct_rebuild_single_liabilities/', 'user\RctControllerNew@rct_rebuild_single_liabilities');

Route::post('/user/rctsaveaspdf_multiple/', 'user\RctControllerNew@rctsaveaspdf_multiple');
Route::post('/user/rctsaveascsv_multiple/', 'user\RctControllerNew@rctsaveascsv_multiple');
Route::post('/user/rctsendemail_multiple/', 'user\RctControllerNew@rctsendemail_multiple');
Route::post('/user/rct_send_bulk_email/', 'user\RctControllerNew@rct_send_bulk_email');
Route::post('/user/get_ckeditor_content_single/', 'user\RctControllerNew@get_ckeditor_content_single');
Route::post('/user/upload_rct_html_form/', 'user\RctControllerNew@upload_rct_html_form');
Route::post('/user/upload_html_form/', 'user\RctControllerNew@upload_html_form');
Route::get('/user/delete_tax_number/', 'user\RctControllerNew@delete_tax_number');


Route::post('/user/update_ros_liability', 'user\Payep30Controller@update_ros_liability');

Route::get('/user/load_table_all', 'user\Payep30Controller@load_table_all');
Route::get('/user/get_employee_numbers', 'user\Payep30Controller@get_employee_numbers');




Route::get('/user/task_manager', 'user\TaskmanagerController@task_manager');
Route::get('/user/park_task', 'user\TaskmanagerController@park_task');
Route::get('/user/taskmanager_search', 'user\TaskmanagerController@taskmanager_search');
Route::get('/user/task_administration', 'user\TaskmanagerController@task_administration');
Route::post('/user/show_infiles', 'user\TaskmanagerController@show_infiles');
Route::post('/user/show_progress_infiles', 'user\TaskmanagerController@show_progress_infiles');
Route::post('/user/show_completion_infiles', 'user\TaskmanagerController@show_completion_infiles');
Route::post('/user/infile_upload_images_taskmanager_add', 'user\TaskmanagerController@infile_upload_images_taskmanager_add');
Route::post('/user/infile_upload_images_taskmanager_progress', 'user\TaskmanagerController@infile_upload_images_taskmanager_progress');
Route::post('/user/infile_upload_images_taskmanager_completion', 'user\TaskmanagerController@infile_upload_images_taskmanager_completion');
Route::post('/user/add_taskmanager_notepad_contents', 'user\TaskmanagerController@add_taskmanager_notepad_contents');
Route::post('/user/taskmanager_notepad_contents_progress', 'user\TaskmanagerController@taskmanager_notepad_contents_progress');
Route::post('/user/taskmanager_notepad_contents_completion', 'user\TaskmanagerController@taskmanager_notepad_contents_completion');




Route::post('/user/clear_session_task_attachments', 'user\TaskmanagerController@clear_session_task_attachments');
Route::post('/user/tasks_remove_dropzone_attachment', 'user\TaskmanagerController@tasks_remove_dropzone_attachment');
Route::post('/user/tasks_remove_notepad_attachment', 'user\TaskmanagerController@tasks_remove_notepad_attachment');
Route::post('/user/show_linked_infiles', 'user\TaskmanagerController@show_linked_infiles');
Route::post('/user/show_linked_progress_infiles', 'user\TaskmanagerController@show_linked_progress_infiles');
Route::post('/user/show_linked_completion_infiles', 'user\TaskmanagerController@show_linked_completion_infiles');
Route::post('/user/create_new_taskmanager_task', 'user\TaskmanagerController@create_new_taskmanager_task');
Route::post('/user/change_taskmanager_user', 'user\TaskmanagerController@change_taskmanager_user');

Route::get('/user/delete_taskmanager_files', 'user\TaskmanagerController@delete_taskmanager_files');
Route::get('/user/delete_taskmanager_notepad', 'user\TaskmanagerController@delete_taskmanager_notepad');
Route::get('/user/delete_taskmanager_infiles', 'user\TaskmanagerController@delete_taskmanager_infiles');
Route::post('/user/taskmanager_change_due_date', 'user\TaskmanagerController@taskmanager_change_due_date');
Route::post('/user/taskmanager_change_allocations', 'user\TaskmanagerController@taskmanager_change_allocations');
Route::post('/user/show_existing_comments', 'user\TaskmanagerController@show_existing_comments');
Route::post('/user/add_comment_specifics', 'user\TaskmanagerController@add_comment_specifics');
Route::post('/user/download_pdf_specifics', 'user\TaskmanagerController@download_pdf_specifics');
Route::post('/user/show_all_allocations', 'user\TaskmanagerController@show_all_allocations');

Route::post('/user/download_pdf_history', 'user\TaskmanagerController@download_pdf_history');
Route::post('/user/download_csv_history', 'user\TaskmanagerController@download_csv_history');

Route::post('/user/copy_task_details', 'user\TaskmanagerController@copy_task_details');
Route::post('/user/get_taskmanager_task_files', 'user\TaskmanagerController@get_taskmanager_task_files');

Route::post('/user/refresh_taskmanager', 'user\TaskmanagerController@refresh_taskmanager');
Route::post('/user/refresh_parktask', 'user\TaskmanagerController@refresh_parktask');
Route::post('/user/taskmanager_mark_complete', 'user\TaskmanagerController@taskmanager_mark_complete');
Route::post('/user/taskmanager_mark_incomplete', 'user\TaskmanagerController@taskmanager_mark_incomplete');

Route::post('/user/search_taskmanager_task', 'user\TaskmanagerController@search_taskmanager_task');
Route::post('/user/update_taskmanager_details', 'user\TaskmanagerController@update_taskmanager_details');
Route::post('/user/show_more_tasks', 'user\TaskmanagerController@show_more_tasks');

Route::post('/user/download_taskmanager_task_pdf', 'user\TaskmanagerController@download_taskmanager_task_pdf');
Route::post('/user/set_progress_value', 'user\TaskmanagerController@set_progress_value');
Route::post('/user/set_avoid_email_taskmanager', 'user\TaskmanagerController@set_avoid_email_taskmanager');

Route::post('/user/get_task_redline_notification', 'user\TaskmanagerController@get_task_redline_notification');
Route::post('/user/request_update', 'user\TaskmanagerController@request_update');

Route::post('/user/add_comment_and_allocate', 'user\TaskmanagerController@add_comment_and_allocate');
Route::post('/user/change_task_name_taskmanager', 'user\TaskmanagerController@change_task_name_taskmanager');

Route::post('/user/park_task_complete', 'user\TaskmanagerController@park_task_complete');
Route::post('/user/park_task_incomplete', 'user\TaskmanagerController@park_task_incomplete');
Route::post('/user/reactivate_park_task', 'user\TaskmanagerController@reactivate_park_task');



/*----MessageUs System------*/
Route::get('/user/directmessaging', 'user\MessageusController@directmessaging');
Route::post('/user/messageus_upload_images_add', 'user\MessageusController@messageus_upload_images_add');
Route::post('/user/messageus_remove_dropzone_attachment', 'user\MessageusController@messageus_remove_dropzone_attachment');
Route::post('/user/messageus_add_comment_to_attachment', 'user\MessageusController@messageus_add_comment_to_attachment');
Route::post('/user/get_attachment_notes', 'user\MessageusController@get_attachment_notes');
Route::post('/user/message_remove_dropzone_attachment', 'user\MessageusController@message_remove_dropzone_attachment');
Route::post('/user/save_message_page_one', 'user\MessageusController@save_message_page_one');

Route::get('/user/directmessaging_page_two', 'user\MessageusController@directmessaging_page_two');
Route::get('/user/directmessaging_page_three', 'user\MessageusController@directmessaging_page_three');
Route::get('/user/messageus_groups', 'user\MessageusController@messageus_groups');
Route::get('/user/messageus_saved_messages', 'user\MessageusController@messageus_saved_messages');


Route::post('/user/save_message_page_two', 'user\MessageusController@save_message_page_two');
Route::post('/user/send_message_later', 'user\MessageusController@send_message_later');
Route::post('/user/send_message_now', 'user\MessageusController@send_message_now');
Route::post('/user/create_group_name', 'user\MessageusController@create_group_name');
Route::post('/user/select_messageus_group', 'user\MessageusController@select_messageus_group');
Route::post('/user/add_selected_member_to_group', 'user\MessageusController@add_selected_member_to_group');
Route::post('/user/remove_selected_member_to_group', 'user\MessageusController@remove_selected_member_to_group');
Route::post('/user/delete_messageus_groups', 'user\MessageusController@delete_messageus_groups');
Route::get('/user/delete_saved_message', 'user\MessageusController@delete_saved_message');
Route::post('/user/choose_messageus_from', 'user\MessageusController@choose_messageus_from');
Route::post('/user/show_messageus_sample_screen', 'user\MessageusController@show_messageus_sample_screen');

Route::post('/user/yearend_export_to_csv', 'user\YearendController@yearend_export_to_csv');
Route::post('/user/update_pms_groups', 'user\MessageusController@update_pms_groups');


