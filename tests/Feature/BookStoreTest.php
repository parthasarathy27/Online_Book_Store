<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookStoreTest extends TestCase
{
    use RefreshDatabase;

    protected $category;
    protected $book;
    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic data for each test
        $this->category = Category::create([
            'name' => 'Programming',
            'slug' => 'programming'
        ]);

        $this->book = Book::create([
            'title' => 'Test Book',
            'author' => 'Test Author',
            'description' => 'Test Description',
            'price' => 29.99,
            'availability' => true,
            'category_id' => $this->category->id,
            'image_url' => 'images/covers/clean_code.png'
        ]);

        $this->user = User::create([
            'name' => 'Demo User',
            'email' => 'user@bookstore.com',
            'password' => bcrypt('user123'),
            'is_admin' => false
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bookstore.com',
            'password' => bcrypt('admin123'),
            'is_admin' => true
        ]);
    }

    public function test_home_page_loads_and_displays_featured_books()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Test Book');
        $response->assertSee('Test Author');
    }

    public function test_book_listing_page_loads_and_filters()
    {
        $response = $this->get('/books');
        $response->assertStatus(200);
        $response->assertSee('Test Book');

        // Filter by category
        $responseCat = $this->get('/books?category=programming');
        $responseCat->assertStatus(200);
        $responseCat->assertSee('Test Book');

        // Filter by search
        $responseSearch = $this->get('/books?search=Test');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Test Book');
    }

    public function test_book_details_page_loads()
    {
        $response = $this->get('/books/' . $this->book->id);
        $response->assertStatus(200);
        $response->assertSee('Test Book');
        $response->assertSee('Test Description');
        $response->assertSee('29.99');
    }

    public function test_google_books_api_integration_displays_on_details_page()
    {
        $response = $this->get('/books/' . $this->book->id);
        $response->assertStatus(200);
        $response->assertSee('Google Books API Integration');
    }

    public function test_guest_can_access_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign In');
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'user@bookstore.com',
            'password' => 'user123'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'user@bookstore.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_cannot_login_via_customer_login()
    {
        $response = $this->post('/login', [
            'email' => 'admin@bookstore.com',
            'password' => 'admin123'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_customer_cannot_login_via_admin_login()
    {
        $response = $this->post('/admin/login', [
            'email' => 'user@bookstore.com',
            'password' => 'user123'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    public function test_regular_user_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->user)->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_create_book()
    {
        $response = $this->actingAs($this->admin)->post('/admin/books', [
            'title' => 'New Book',
            'author' => 'New Author',
            'price' => 19.99,
            'description' => 'New Description',
            'availability' => '1',
            'category_id' => $this->category->id
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertDatabaseHas('books', [
            'title' => 'New Book',
            'author' => 'New Author',
            'price' => 19.99
        ]);
    }

    public function test_admin_can_edit_book()
    {
        $response = $this->actingAs($this->admin)->put('/admin/books/' . $this->book->id, [
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'price' => 39.99,
            'description' => 'Updated Description',
            'category_id' => $this->category->id
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertDatabaseHas('books', [
            'id' => $this->book->id,
            'title' => 'Updated Title',
            'price' => 39.99,
            'availability' => 0
        ]);
    }

    public function test_admin_can_delete_book()
    {
        $response = $this->actingAs($this->admin)->get('/admin/books/' . $this->book->id . '/delete');

        $response->assertRedirect('/admin/dashboard');
        $this->assertDatabaseMissing('books', [
            'id' => $this->book->id
        ]);
    }

    public function test_guest_can_access_registration()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Create Account');
    }

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'New Registered User',
            'email' => 'newuser@bookstore.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@bookstore.com',
            'name' => 'New Registered User'
        ]);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        $response = $this->post('/register', [
            'name' => 'Duplicate User',
            'email' => 'user@bookstore.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_purchase_book_and_view_it_on_dashboard()
    {
        $response = $this->actingAs($this->user)->post('/books/' . $this->book->id . '/buy', [
            'client_name' => 'Test Buyer',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'TS',
            'zip' => '12345',
            'phone' => '123-456-7890'
        ]);

        $this->assertDatabaseHas('orders', [
            'book_id' => $this->book->id,
            'user_id' => $this->user->id,
            'client_name' => 'Test Buyer',
            'address' => '123 Test Street'
        ]);

        $order = Order::where('user_id', $this->user->id)->first();
        $response->assertRedirect('/orders/' . $order->id . '/confirmation');

        // Check confirmation details
        $confirmationResponse = $this->actingAs($this->user)->get('/orders/' . $order->id . '/confirmation');
        $confirmationResponse->assertStatus(200);
        $confirmationResponse->assertSee('Test Book');
        $confirmationResponse->assertSee('Test Buyer');
        $confirmationResponse->assertSee('123 Test Street');

        // Check if the dashboard displays the order details in pending state
        $dashboardResponse = $this->actingAs($this->user)->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Test Book');
        $dashboardResponse->assertSee('Test Buyer');
        $dashboardResponse->assertSee('123 Test Street');
        $dashboardResponse->assertSee('Pending Confirmation');

        // Confirm the order to make receipt available
        $order->status = 'confirmed';
        $order->save();

        // Refresh and check that View Receipt is now visible
        $dashboardResponse2 = $this->actingAs($this->user)->get('/dashboard');
        $dashboardResponse2->assertStatus(200);
        $dashboardResponse2->assertSee('View Receipt');
    }

    public function test_admin_can_manage_and_delete_orders()
    {
        // Place an order first
        $order = Order::create([
            'book_id' => $this->book->id,
            'user_id' => $this->user->id,
            'price' => $this->book->price,
            'client_name' => 'Order Manager Client',
            'address' => '456 Order Ave',
            'city' => 'Order City',
            'state' => 'OS',
            'zip' => '54321',
            'phone' => '987-654-3210'
        ]);

        // Admin checks the dashboard
        $response = $this->actingAs($this->admin)->get('/admin/dashboard?tab=orders');
        $response->assertStatus(200);
        $response->assertSee('Order Manager Client');

        // Admin cancels/deletes the order
        $deleteResponse = $this->actingAs($this->admin)->get('/admin/orders/' . $order->id . '/delete');
        $deleteResponse->assertRedirect('/admin/dashboard?tab=orders');

        $this->assertDatabaseMissing('orders', [
            'id' => $order->id
        ]);
    }

    public function test_admin_can_change_password()
    {
        $response = $this->actingAs($this->admin)->post('/admin/password', [
            'current_password' => 'admin123',
            'new_password' => 'newadminpassword123',
            'new_password_confirmation' => 'newadminpassword123'
        ]);

        $response->assertRedirect('/admin/dashboard?tab=password');
        
        // Try logging in with the new password
        \Illuminate\Support\Facades\Auth::logout();
        
        $loginResponse = $this->post('/admin/login', [
            'email' => 'admin@bookstore.com',
            'password' => 'newadminpassword123'
        ]);
        $loginResponse->assertRedirect('/admin/dashboard');
    }

    public function test_customer_can_edit_and_update_own_order()
    {
        $order = Order::create([
            'book_id' => $this->book->id,
            'user_id' => $this->user->id,
            'price' => $this->book->price,
            'client_name' => 'Original Name',
            'address' => '123 Original St',
            'city' => 'Original City',
            'state' => 'OS',
            'zip' => '12345',
            'phone' => '123-456-7890'
        ]);

        $response = $this->actingAs($this->user)->get('/orders/' . $order->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('Original Name');

        $updateResponse = $this->actingAs($this->user)->put('/orders/' . $order->id, [
            'client_name' => 'Updated Name',
            'address' => '789 Updated St',
            'city' => 'Updated City',
            'state' => 'US',
            'zip' => '98765',
            'phone' => '987-654-3210'
        ]);

        $updateResponse->assertRedirect('/orders/' . $order->id . '/confirmation');
        
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'client_name' => 'Updated Name',
            'address' => '789 Updated St'
        ]);
    }

    public function test_customer_cannot_edit_another_users_order()
    {
        $otherUser = User::factory()->create([
            'is_admin' => false
        ]);

        $order = Order::create([
            'book_id' => $this->book->id,
            'user_id' => $otherUser->id,
            'price' => $this->book->price,
            'client_name' => 'Other Name',
            'address' => 'Other St',
            'city' => 'Other City',
            'state' => 'OS',
            'zip' => '12345',
            'phone' => '123-456-7890'
        ]);

        $response = $this->actingAs($this->user)->get('/orders/' . $order->id . '/edit');
        $response->assertStatus(403);
    }

    public function test_admin_can_edit_and_update_any_order()
    {
        $order = Order::create([
            'book_id' => $this->book->id,
            'user_id' => $this->user->id,
            'price' => $this->book->price,
            'client_name' => 'User Placed Name',
            'address' => 'User St',
            'city' => 'User City',
            'state' => 'US',
            'zip' => '12345',
            'phone' => '123-456-7890'
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/orders/' . $order->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('User Placed Name');

        $updateResponse = $this->actingAs($this->admin)->put('/admin/orders/' . $order->id, [
            'client_name' => 'Admin Updated Name',
            'address' => 'Admin Updated St',
            'city' => 'Admin City',
            'state' => 'AS',
            'zip' => '54321',
            'phone' => '000-000-0000',
            'status' => 'confirmed'
        ]);

        $updateResponse->assertRedirect('/admin/dashboard?tab=orders');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'client_name' => 'Admin Updated Name',
            'address' => 'Admin Updated St',
            'status' => 'confirmed'
        ]);
    }

    public function test_order_receipt_access_depends_on_status()
    {
        $order = Order::create([
            'book_id' => $this->book->id,
            'user_id' => $this->user->id,
            'price' => $this->book->price,
            'client_name' => 'John Doe',
            'address' => '123 Main St',
            'city' => 'Metropolis',
            'state' => 'NY',
            'zip' => '10001',
            'phone' => '555-555-5555',
            'status' => 'pending'
        ]);

        // Accessing the receipt of a pending order directly should redirect to dashboard
        $response = $this->actingAs($this->user)->get('/orders/' . $order->id . '/confirmation');
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');

        // Admin can still view pending order receipt
        $adminResponse = $this->actingAs($this->admin)->get('/orders/' . $order->id . '/confirmation');
        $adminResponse->assertStatus(200);

        // Update status to confirmed
        $order->status = 'confirmed';
        $order->save();

        // Customer can now view it
        $confirmedResponse = $this->actingAs($this->user)->get('/orders/' . $order->id . '/confirmation');
        $confirmedResponse->assertStatus(200);
        $confirmedResponse->assertSee('John Doe');
    }
}
