<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\File;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard list of books.
     */
    public function index()
    {
        $books = Book::with('category')->latest()->paginate(10, ['*'], 'books_page');
        $orders = Order::with(['user', 'book'])->latest()->paginate(10, ['*'], 'orders_page');
        return view('admin.dashboard', compact('books', 'orders'));
    }

    /**
     * Show form to create a new book.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.create', compact('categories'));
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'availability' => 'nullable|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = $request->only(['title', 'author', 'category_id', 'price', 'description']);
        $data['availability'] = $request->has('availability');

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/covers'), $filename);
            $data['image_url'] = 'images/covers/' . $filename;
        } else {
            $data['image_url'] = 'images/covers/clean_code.png';
        }

        Book::create($data);

        return redirect()->route('admin.dashboard')->with('success', 'Book added successfully!');
    }

    /**
     * Show form to edit an existing book.
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('admin.edit', compact('book', 'categories'));
    }

    /**
     * Update an existing book.
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'availability' => 'nullable|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = $request->only(['title', 'author', 'category_id', 'price', 'description']);
        $data['availability'] = $request->has('availability');

        // Handle Image Upload
        if ($request->hasFile('image')) {
            if ($book->image_url && File::exists(public_path($book->image_url)) && strpos($book->image_url, 'images/covers/') === 0) {
                $defaults = ['images/covers/clean_code.png', 'images/covers/wealth_mindset.png', 'images/covers/cosmic_odyssey.png', 'images/covers/mastering_habits.png'];
                if (!in_array($book->image_url, $defaults)) {
                    File::delete(public_path($book->image_url));
                }
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/covers'), $filename);
            $data['image_url'] = 'images/covers/' . $filename;
        }

        $book->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'Book updated successfully!');
    }

    /**
     * Delete a book.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if ($book->image_url && File::exists(public_path($book->image_url))) {
            $defaults = ['images/covers/clean_code.png', 'images/covers/wealth_mindset.png', 'images/covers/cosmic_odyssey.png', 'images/covers/mastering_habits.png'];
            if (!in_array($book->image_url, $defaults)) {
                File::delete(public_path($book->image_url));
            }
        }

        $book->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Book deleted successfully!');
    }

    /**
     * Delete/cancel a customer order.
     */
    public function destroyOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'orders'])->with('success', 'Order cancelled and deleted successfully!');
    }

    /**
     * Update the administrator's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $admin = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $admin->password)) {
            return redirect()->route('admin.dashboard', ['tab' => 'password'])->withErrors([
                'current_password' => 'The provided password does not match your current password.'
            ]);
        }

        $admin->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $admin->save();

        return redirect()->route('admin.dashboard', ['tab' => 'password'])->with('success', 'Password updated successfully!');
    }

    /**
     * Show edit form for order details (Admin).
     */
    public function editOrder($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update order details (Admin).
     */
    public function updateOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'client_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'phone' => 'required|string|max:30',
            'status' => 'required|string|in:pending,confirmed,shipped,delivered',
        ]);

        $order->update($request->only(['client_name', 'address', 'city', 'state', 'zip', 'phone', 'status']));

        return redirect()->route('admin.dashboard', ['tab' => 'orders'])->with('success', 'Order details updated successfully!');
    }
}
