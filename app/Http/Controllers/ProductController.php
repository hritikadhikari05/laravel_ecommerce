<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class ProductController extends Controller
{
    //
    public function index()
    {
        return view('product');
    }

    public function getHomepage(Request $request)
    {
        $userId = $request->session()->get('userId');
        $products = DB::table('products')->get();
        return view("homepage", ['userId' => $userId, 'products' => $products]);
    }

    //Get dashboard
    public function getDasbboard(Request $request)
    {
        $userId = $request->session()->get('userId');
        $isAdmin = $request->session()->get('isAdmin');
        if ($isAdmin) {
            return view("dashboard", ['userId' => $userId]);
        } else {
            return redirect()->route('homepage');
        }
    }

    //Get Manage Product
    public function manageProduct(Request $request)
    {
        $userId = $request->session()->get('userId');
        $isAdmin = $request->session()->get('isAdmin');
        if ($isAdmin) {
            $products = DB::table('products')->get();
            return view("manageProducts", ['userId' => $userId, 'products' => $products]);
        } else {
            return redirect()->route('homepage');
        }
    }

    //Signin user
    public function signin(Request $request)
    {
        $email = $request->input('email');
        $plainTextPassword = $request->input('password');

        $user = DB::table('users')->where('email', $email)->first();
        // echo $user;

        if ($user) {
            // Hash the provided plain text password with MD5
            $hashedPassword = md5($plainTextPassword);

            // Compare the hashed password with the one stored in the database
            if ($hashedPassword === $user->password) {
                // Store user ID in the session
                $request->session()->put('userId', $user->id);

                if ($user->isAdmin == 1) {
                    $request->session()->put('isAdmin', true);
                }
                // $request->session()->put('isAdmin', $user->isAdmin);

                return redirect()->route('homepage')->with('status', 'Login successful');
            } else {
                $error['login'] = "Invalid Credentials";
                return redirect()->route('login')->withErrors($error);
            }
        } else {
            $error['login'] = "Invalid Credentials";
            return redirect()->route('login')->withErrors($error);
        }
    }

    //Signup new user
    public function signUp(Request $request, Response $response)
    {
        $fullName = $request->input('name');
        $password = $request->input('password');
        $cpassword = $request->input('cpassword');
        $email = $request->input('email');
        $phoneNo = $request->input('phoneNo');
        $isAdmin = false;
        $encryptedPassword = md5($password);
        $createdAt = date('Y-m-d H:i:s');

        $error = array();

        if ($password == $cpassword) {
            //Check If the user already exists
            $user = DB::table('users')->where('email', $email)->get();
            if (count($user) > 0) {
                $error['signup'] = "User already exists";
                return redirect()->route('signup')->withErrors($error);
            } else {
                //Insert into database
                $data = array('fullName' => $fullName, 'password' => $encryptedPassword, 'email' => $email, 'phoneNo' => $phoneNo, 'isAdmin' => $isAdmin, 'createdAt' => $createdAt);
                DB::table('users')->insert($data);

                //Add userid to session and coookies
                $user = DB::table('users')->where('email', $email)->get();

                //Add to session
                $request->session()->put('userId', $user[0]->id);

                //If user is admin then
                if ($user[0]->isAdmin == 1) {
                    $request->session()->put('isAdmin', true);
                }

                //Add Cookies for 30 day
                $minutes = 60 * 24 * 30;
                $response->withCookie(cookie('userId', $user[0]->id, $minutes));

                return redirect()->route('homepage');
            }
        } else {
            // echo "Password does not match";
            $error['password'] = "Password does not match";
            return redirect()->route('signup')->withErrors($error);
        }
    }

    //Get Add Product Page
    public function addProduct(Request $request)
    {
        $userId = $request->session()->get('userId');
        $isAdmin = $request->session()->get('isAdmin');
        if ($isAdmin) {
            return view("addProduct", ['userId' => $userId]);
        } else {
            return redirect()->route('homepage');
        }
    }

    //Add new Product
    public function addNewProduct(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $category = $request->input('category');
        $price = $request->input('price');
        $stockqty = $request->input('stockqty');
        $manufacturer = $request->input('manufacturer');
        $dsale = $request->input('dsale');
        //Image
        $uploadedFile = $request->file('image');
        $imagePath = $uploadedFile->store('images', 'public');

        // $createdAt = date('Y-m-d H:i:s');

        $data = array('productTitle' => $title, 'description' => $description, 'image_path' => $imagePath, 'category' => $category, 'price' => $price, 'stockQuantity' => $stockqty, 'manufacturer' => $manufacturer, 'discount' => $dsale);
        DB::table('products')->insert($data);

        return redirect()->route('dashboard');
    }

    //Edit Product
    public function editProduct(Request $request, $id)
    {
        $userId = $request->session()->get('userId');
        $isAdmin = $request->session()->get('isAdmin');
        if ($isAdmin) {
            $product = DB::table('products')->where('productID', $id)->first();
            return view("editProduct", ['userId' => $userId, 'product' => $product]);
        } else {
            return redirect()->route('homepage');
        }
    }

    //Update Product
    public function updateProduct(Request $request, $id)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $category = $request->input('category');
        $price = $request->input('price');
        $stockqty = $request->input('stockqty');
        $manufacturer = $request->input('manufacturer');
        $dsale = $request->input('dsale');
        //Image
        $uploadedFile = $request->file('image');

        $product = DB::table('products')->where('productID', $id)->first();

        $imagePath = $uploadedFile ?? $product->image_path;

        if ($uploadedFile) {
            $imagePath = $uploadedFile->store('images', 'public');
        }
        // $createdAt = date('Y-m-d H:i:s');

        $data = array('productTitle' => $title, 'description' => $description, 'image_path' => $imagePath, 'category' => $category, 'price' => $price, 'stockQuantity' => $stockqty, 'manufacturer' => $manufacturer, 'discount' => $dsale);
        DB::table('products')->where('productID', $id)->update($data);

        return redirect()->route('manage-product');
    }

    //Delete Product
    public function deleteProduct(Request $request, $id)
    {
        //TODO: Check if user is the creator of the product
        // $userId = $request->session()->get('userId');
        $isAdmin = $request->session()->get('isAdmin');
        if ($isAdmin) {
            DB::table('products')->where('productID', $id)->delete();
            return redirect()->route('manage-product');
        } else {
            return redirect()->route('homepage');
        }
    }
}
