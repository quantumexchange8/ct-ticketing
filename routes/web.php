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

Route::get('/documentation/{title}', [MemberController::class, 'documentation'])->name('documentation');

Route::get('/support', [MemberController::class, 'support'])->name('support');

Route::get('/open-ticket', [MemberController::class, 'openTicket'])->name('openTicket');
Route::post('/submit-ticket', [MemberController::class, 'submitTicket'])->name('submitTicket');

Route::get('/search-support-tools', [SearchController::class, 'searchSupportTools'])->name('searchSupportTools');
Route::get('/search-documentation', [SearchController::class, 'searchDocumentation'])->name('searchDocumentation');

Route::get('/content', [MemberController::class, 'content'])->name('content');


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

    Route::get('/ticket', [AdminController::class, 'ticket'])->name('ticket');
    Route::get('/get-ticket-by-status', [AdminController::class, 'getTicketByStatus'])->name('getTicketByStatus');
    Route::post('/update-ticket-kanban', [AdminController::class, 'updateTicketKanban'])->name('updateTicketKanban');

    Route::get('/create-ticket', [AdminController::class, 'createTicket'])->name('createTicket');
    Route::post('/add-ticket', [AdminController::class, 'addTicket'])->name('addTicket');
    Route::get('/edit-ticket/{id}', [AdminController::class, 'editTicket'])->name('editTicket');
    Route::post('/update-ticket/{id}', [AdminController::class, 'updateTicket'])->name('updateTicket');
    Route::delete('/delete-ticket/{id}', [AdminController::class, 'deleteTicket'])->name('deleteTicket');

    Route::get('/category-summary/{supportCategory}', [AdminController::class, 'categorySumm'])->name('categorySumm');

    // Administration
    Route::get('/title-summary', [AdminController::class, 'titleSumm'])->name('titleSumm');
    Route::get('/view-more-subtitle/{id}', [AdminController::class, 'viewMoreSubtitle'])->name('viewMoreSubtitle');
    Route::get('/create-title', [AdminController::class, 'createTitle'])->name('createTitle');
    Route::post('/add-title', [AdminController::class, 'addTitle'])->name('addTitle');
    Route::get('/edit-title/{id}', [AdminController::class, 'editTitle'])->name('editTitle');
    Route::post('/update-title/{id}', [AdminController::class, 'updateTitle'])->name('updateTitle');
    Route::delete('/delete-title/{id}', [AdminController::class, 'deleteTitle'])->name('deleteTitle');

    Route::get('/subtitle-summary/{title}', [AdminController::class, 'subtitleSumm'])->name('subtitleSumm');
    Route::get('/view-more-content/{id}', [AdminController::class, 'viewMoreContent'])->name('viewMoreContent');
    Route::get('/create-subtitle/{title}', [AdminController::class, 'createSubtitle'])->name('createSubtitle');
    Route::post('/add-subtitle/{title}', [AdminController::class, 'addSubtitle'])->name('addSubtitle');
    Route::get('/edit-subtitle/{id}', [AdminController::class, 'editSubtitle'])->name('editSubtitle');
    Route::post('/update-subtitle/{id}', [AdminController::class, 'updateSubtitle'])->name('updateSubtitle');
    Route::delete('/delete-subtitle/{id}', [AdminController::class, 'deleteSubtitle'])->name('deleteSubtitle');

    Route::get('/content-summary/{subtitle}', [AdminController::class, 'contentSumm'])->name('contentSumm');
    Route::get('/create-content', [AdminController::class, 'createContent'])->name('createContent');
    Route::post('/add-content', [AdminController::class, 'addContent'])->name('addContent');
    Route::get('/edit-content/{id}', [AdminController::class, 'editContent'])->name('editContent');
    Route::post('/update-content/{id}', [AdminController::class, 'updateContent'])->name('updateContent');
    Route::delete('/delete-content/{id}', [AdminController::class, 'deleteContent'])->name('deleteContent');

    Route::get('/support-category-summary', [AdminController::class, 'supportCategorySumm'])->name('supportCategorySumm');
    Route::get('/create-category', [AdminController::class, 'createCategory'])->name('createCategory');
    Route::post('/add-category', [AdminController::class, 'addCategory'])->name('addCategory');
    Route::get('/edit-category/{id}', [AdminController::class, 'editCategory'])->name('editCategory');
    Route::post('/update-category/{id}', [AdminController::class, 'updateCategory'])->name('updateCategory');
    Route::delete('/delete-category/{id}', [AdminController::class, 'deleteCategory'])->name('deleteCategory');

    Route::get('/support-sub-summary/{supportCategory}', [AdminController::class, 'supportSubSumm'])->name('supportSubSumm');
    Route::get('/create-sub/{supportCategory}', [AdminController::class, 'createSub'])->name('createSub');
    Route::post('/add-sub/{supportCategory}', [AdminController::class, 'addSub'])->name('addSub');
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

    // Preview documentation
    Route::get('/view-content/{title}', [AdminController::class, 'viewContent'])->name('viewContent');

});





