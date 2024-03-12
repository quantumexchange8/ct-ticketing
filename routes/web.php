<?php
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubmitTicket;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [MemberController::class, 'dashboard'])->name('dashboard');
Route::get('/select-project/{projectId}', [MemberController::class, 'selectProject'])->name('selectProject');
Route::get('/documentation/{title}', [MemberController::class, 'documentation'])->name('documentation');

Route::get('/support/{project}', [MemberController::class, 'support'])->name('support');

Route::get('/open-ticket/{project}', [MemberController::class, 'openTicket'])->name('openTicket');
Route::post('/submit-ticket', [MemberController::class, 'submitTicket'])->name('submitTicket');

Route::get('/release-note/{project}', [MemberController::class, 'releaseNote'])->name('releaseNote');

Route::get('/search-support-tools', [SearchController::class, 'searchSupportTools'])->name('searchSupportTools');
Route::get('/search-documentation', [SearchController::class, 'searchDocumentation'])->name('searchDocumentation');


// Admin

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login-post', [LoginController::class, 'loginPost'])->name('loginPost');

Route::group(['middleware' => 'auth'], function () {
    // Main
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('adminDashboard');
    Route::get('/helpdesk', [AdminController::class, 'helpdesk'])->name('helpdesk');
    Route::get('/get-ticket', [AdminController::class, 'getTicket'])->name('getTicket');

    Route::get('/ticket-summary/{status}', [AdminController::class, 'ticketSumm'])->name('ticketSumm');
    Route::get('/view-ticket-image/{id}', [AdminController::class, 'viewTicketImage'])->name('viewTicketImage');
    Route::get('/view-ticket/{id}', [AdminController::class, 'viewTicket'])->name('viewTicket');

    Route::get('/unassigned-ticket', [AdminController::class, 'unassignedTicket'])->name('unassignedTicket');

    Route::get('/ticket', [AdminController::class, 'ticket'])->name('ticket');
    Route::get('/get-ticket-by-status', [AdminController::class, 'getTicketByStatus'])->name('getTicketByStatus');
    Route::post('/update-ticket-kanban', [AdminController::class, 'updateTicketKanban'])->name('updateTicketKanban');

    Route::get('/create-ticket', [AdminController::class, 'createTicket'])->name('createTicket');
    Route::post('/add-ticket', [AdminController::class, 'addTicket'])->name('addTicket');
    Route::get('/edit-ticket/{id}', [AdminController::class, 'editTicket'])->name('editTicket');
    Route::post('/update-ticket/{id}', [AdminController::class, 'updateTicket'])->name('updateTicket');
    Route::delete('/delete-ticket/{id}', [AdminController::class, 'deleteTicket'])->name('deleteTicket');

    Route::post('/add-note', [AdminController::class, 'addNote'])->name('addNote');
    Route::get('/edit-note/{id}', [AdminController::class, 'editNote'])->name('editNote');
    Route::post('/update-note/{id}', [AdminController::class, 'updateNote'])->name('updateNote');
    Route::delete('/delete-note/{id}', [AdminController::class, 'deleteNote'])->name('deleteNote');

    Route::get('/category-summary/{supportCategory}', [AdminController::class, 'categorySumm'])->name('categorySumm');

    Route::get('/project-ticket/{project}', [AdminController::class, 'projectTicket'])->name('projectTicket');

    Route::get('/performance', [AdminController::class, 'performance'])->name('performance');
    Route::get('/get-performance', [AdminController::class, 'getPerformance'])->name('getPerformance');
    Route::get('/view-performance/{id}', [AdminController::class, 'viewPerformance'])->name('viewPerformance');

    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/update-profile/{id}', [AdminController::class, 'updateProfile'])->name('updateProfile');
    Route::delete('/delete-profile-picture', [AdminController::class, 'deleteProfilePicture'])->name('deleteProfilePicture');

    Route::get('/email-signature', [AdminController::class, 'emailSignature'])->name('emailSignature');
    Route::get('/get-email-signature', [AdminController::class, 'getEmailSignature'])->name('getEmailSignature');
    Route::post('/update-email-signature', [AdminController::class, 'updateEmailSignature'])->name('updateEmailSignature');

    // Project
    Route::get('/project-summary', [AdminController::class, 'projectSumm'])->name('projectSumm');
    Route::get('/create-project', [AdminController::class, 'createProject'])->name('createProject');
    Route::post('/add-project', [AdminController::class, 'addProject'])->name('addProject');
    Route::get('/edit-project/{id}', [AdminController::class, 'editProject'])->name('editProject');
    Route::post('/update-project/{id}', [AdminController::class, 'updateProject'])->name('updateProject');
    Route::delete('/delete-project/{id}', [AdminController::class, 'deleteProject'])->name('deleteProject');

    // Administration
    Route::get('/title-summary/{project}', [AdminController::class, 'titleSumm'])->name('titleSumm');
    Route::get('/view-more-subtitle/{id}', [AdminController::class, 'viewMoreSubtitle'])->name('viewMoreSubtitle');
    Route::get('/create-title/{project}', [AdminController::class, 'createTitle'])->name('createTitle');
    Route::post('/add-title/{project}', [AdminController::class, 'addTitle'])->name('addTitle');
    Route::get('/edit-title/{id}', [AdminController::class, 'editTitle'])->name('editTitle');
    Route::post('/update-title/{id}', [AdminController::class, 'updateTitle'])->name('updateTitle');
    Route::delete('/delete-title/{id}', [AdminController::class, 'deleteTitle'])->name('deleteTitle');

    Route::get('/view-more-content/{id}', [AdminController::class, 'viewMoreContent'])->name('viewMoreContent');
    Route::post('/add-subtitle/{title}', [AdminController::class, 'addSubtitle'])->name('addSubtitle');
    Route::get('/edit-subtitle/{id}', [AdminController::class, 'editSubtitle'])->name('editSubtitle');
    Route::post('/update-subtitle/{id}', [AdminController::class, 'updateSubtitle'])->name('updateSubtitle');
    Route::delete('/delete-subtitle/{id}', [AdminController::class, 'deleteSubtitle'])->name('deleteSubtitle');

    Route::get('/create-content', [AdminController::class, 'createContent'])->name('createContent');
    Route::post('/add-content', [AdminController::class, 'addContent'])->name('addContent');
    Route::get('/edit-content/{id}', [AdminController::class, 'editContent'])->name('editContent');
    Route::post('/update-content/{id}', [AdminController::class, 'updateContent'])->name('updateContent');
    Route::delete('/delete-content/{id}', [AdminController::class, 'deleteContent'])->name('deleteContent');

    Route::get('/support-tool', [AdminController::class, 'supportTool'])->name('supportTool');

    Route::get('/enhancement', [AdminController::class, 'enhancement'])->name('enhancement');
    Route::get('/enhancement-summary/{project}', [AdminController::class, 'enhancementSumm'])->name('enhancementSumm');

    Route::get('/create-enhancement', [AdminController::class, 'createEnhancement'])->name('createEnhancement');

    Route::post('/add-enhancement/{project}', [AdminController::class, 'addEnhancement'])->name('addEnhancement');
    Route::get('/edit-enhancement/{id}', [AdminController::class, 'editEnhancement'])->name('editEnhancement');
    Route::post('/update-enhancement/{id}', [AdminController::class, 'updateEnhancement'])->name('updateEnhancement');
    Route::delete('/delete-enhancement/{id}', [AdminController::class, 'deleteEnhancement'])->name('deleteEnhancement');

    Route::get('/support-category', [AdminController::class, 'supportCategory'])->name('supportCategory');

    Route::get('/support-category-summary/{project}', [AdminController::class, 'supportCategorySumm'])->name('supportCategorySumm');
    Route::get('/create-category', [AdminController::class, 'createCategory'])->name('createCategory');
    Route::post('/add-category', [AdminController::class, 'addCategory'])->name('addCategory');
    Route::get('/edit-category/{id}', [AdminController::class, 'editCategory'])->name('editCategory');
    Route::post('/update-category/{id}', [AdminController::class, 'updateCategory'])->name('updateCategory');
    Route::delete('/delete-category/{id}', [AdminController::class, 'deleteCategory'])->name('deleteCategory');

    Route::get('/support-sub-summary/{supportCategory}/{project}', [AdminController::class, 'supportSubSumm'])->name('supportSubSumm');
    // Route::get('/create-sub/{supportCategory}/{project}', [AdminController::class, 'createSub'])->name('createSub');
    Route::get('/create-sub/{project}', [AdminController::class, 'createSub'])->name('createSub');
    Route::post('/add-sub/{project}', [AdminController::class, 'addSub'])->name('addSub');
    Route::get('/edit-sub/{id}', [AdminController::class, 'editSub'])->name('editSub');
    Route::post('/update-sub/{id}', [AdminController::class, 'updateSub'])->name('updateSub');
    Route::delete('/delete-sub/{id}', [AdminController::class, 'deleteSub'])->name('deleteSub');

    Route::get('/ticket-status', [AdminController::class, 'ticketStatus'])->name('ticketStatus');
    Route::get('/create-ticket-status', [AdminController::class, 'createTicketStatus'])->name('createTicketStatus');
    Route::post('/add-ticket-status', [AdminController::class, 'addTicketStatus'])->name('addTicketStatus');
    Route::get('/edit-ticket-status/{id}', [AdminController::class, 'editTicketStatus'])->name('editTicketStatus');
    Route::post('/update-ticket-status/{id}', [AdminController::class, 'updateTicketStatus'])->name('updateTicketStatus');
    Route::delete('/delete-ticket-status/{id}', [AdminController::class, 'deleteTicketStatus'])->name('deleteTicketStatus');

    Route::get('/admin-summary', [AdminController::class, 'adminSumm'])->name('adminSumm');
    Route::get('/create-admin', [AdminController::class, 'createAdmin'])->name('createAdmin');
    Route::post('/add-admin', [AdminController::class, 'addAdmin'])->name('addAdmin');
    Route::get('/edit-admin/{id}', [AdminController::class, 'editAdmin'])->name('editAdmin');
    Route::post('/update-admin/{id}', [AdminController::class, 'updateAdmin'])->name('updateAdmin');
    Route::delete('/delete-admin/{id}', [AdminController::class, 'deleteAdmin'])->name('deleteAdmin');

});





