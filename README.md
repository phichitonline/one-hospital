<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel 9 API and Authen with Sanctum

## Backend : Setup Authen with Sanctum

Step 1: Config database in .env file

    // ทำการสร้างฐานข้อมูลแล้วตั้งค่าเชื่อมต่อในไฟล์ .env


Step 2: Install Laravel Sanctum.

    composer require laravel/sanctum


Step 3:Publish the Sanctum configuration and migration files.
  
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"


Step 4:Run your database migrations.

    // แก้ไขไฟล์ใน migrations/...users...
    // เพิ่มฟิลด์สำหรับเก็บข้อมูล user เพิ่มเติมอีกนิดหน่อยครับ

    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('fullname');
        $table->string('username');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('tel');
        $table->string('avatar')->nullable();
        $table->tinyInteger('role')->default(2);
        $table->rememberToken();
        $table->timestamps();
    });

    // เสร็จแล้วก็ทำการสร้างตารางกันเลย
    php artisan migrate
  

Step 5:Add the Sanctum's middleware.(app/Http/Kernel.php)

    // เอา comment ออกได้เลยครับ

    'api' => [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
  

 Step 6:To use tokens for users model.

    // ตรวจสอบการเรียกใช้งาน HasApiTokens

    use Laravel\Sanctum\HasApiTokens;

    class User extends Authenticatable
    {
        use HasApiTokens, HasFactory, Notifiable;
    }

    // และเพิ่มฟิลด์ให้ครบตามที่เราได้เพิ่มเติมเข้าไปครับ

    protected $fillable = [
        'fullname',
        'username',
        'email',
        'email_verified_at',
        'password',
        'tel',
        'avatar',
        'role',
    ];


Step 7:Create a controller for register user and login/logout.

    // สร้าง AuthController

    php artisan make:controller AuthController

    // ไฟล์ app/Http/Controllers/AuthController.php

    public function register(Request $request)
    {
        $fields = $request->validate([
            'fullname' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'tel' => 'required'
        ]);

        $user = User::create([
            'fullname' => $fields['fullname'],
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'tel' => $fields['tel']
        ]);

        $token = $user->createToken($request->userAgent(), ["2"])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);

    }

    public function adminregister(Request $request)
    {
        $fields = $request->validate([
            'fullname' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'tel' => 'required',
            'role' => 'required|integer'
        ]);

        $user = User::create([
            'fullname' => $fields['fullname'],
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'tel' => $fields['tel'],
            'role' => $fields['role']
        ]);

        $token = $user->createToken($request->userAgent(), ["$user->role"])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);

    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email',$fields['email'])->first();
        // Chect password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid Login'
            ], 401);
        } else {
            $user->tokens()->delete(); // ลบ Token
            $token = $user->createToken($request->userAgent(), ["$user->role"])->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response($response, 201);
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }


Step 8:Create route in the routes/api.php file

    Route::post('login', [AuthController::class, 'login']);
    // Route::post('register', [AuthController::class, 'register']); // comment ไว้ไม่ให้ register เองได้

    // Protected route group
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::post('adminregister', [AuthController::class, 'adminregister']);
        Route::post('logout', [AuthController::class, 'logout']);
    });


Step 9:Setup API version in file.
        app/Providers/RouteServiceProvider.php
        app/Http/Middleware/APIVersion.php



## Frontend : Get data from API with token.

Step 1:Creat controller

    // เก็บ token ไว้ในไฟล์ .env แล้วเรียกมาใช้ใน controller

        $apitoken = env('API_TOKEN');

        $collection = Http::withHeaders([
            'Authorization'=> 'Bearer '.$apitoken.'',
        ])->get('https://apiservice.tphcp.go.th/api/products');

        return view('test',[
            'collection' => $collection['data'],
        ]);



## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
