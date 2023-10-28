<?php

use App\Models\Cart;
use App\Models\Employee;
use App\Models\Favorate;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Person;
use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', function (Request $request) {

    $email =  $request->input('email');
    $password = $request->input('password');
    $user =  Person::where('email', '=', $email)->where('password', '=', $password)->first();
    if ($user) {
        unset($user->password);
        return Response::json(
            $user,
            200
        );
    } else {
        return Response::json(['error' => 'User not found'], 404);
    }
});


Route::post('/signup', function (Request $request) {

    $user = Person::where('email', '=', $request->input('email'))->first();
    if ($user) {
        return Response::json(['error' => 'email already exist'], 201);
    } else {
        $user = new Person();
        $user->firstName = $request->input('firstName');
        $user->lastName = $request->input('lastName');
        $user->email = $request->input('email');
        $user->type = $request->input('type');
        $user->phone = $request->input('phone');
        $user->password = $request->input('password');

        $user->image = '';
        if ($file = $request->file('image')) {
            $imageName = $file->getClientOriginalName() . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
            $user->image = $imageName;
        }

        if ($user->save()) {
            return Response::json(
                $user,
                200
            );
        } else {
            return Response::json(
                ['error' => 'can not save user'],
                202
            );
        }
    }
});

Route::post('/profile', function (Request $request) {
    $id =  $request->input('id');

    $user =  Person::where('id', '=', $id)->first();

    if ($user) {
        return Response::json(
            $user,
            200
        );
    } else {
        return Response::json(['error' => 'User not found'], 404);
    }
});


Route::post('/editProfile', function (Request $request) {
    $id =  $request->input('id');
    $user =  Person::where('id', '=', $id)->first();
    if ($user) {
        if ($request->has('firstName')) $user->firstName = $request->input('firstName');
        if ($request->has('lastName')) $user->lastName = $request->input('lastName');
        if ($request->has('phone')) $user->phone = $request->input('phone');
        if ($request->has('email')) $user->email = $request->input('email');
        if ($request->has('password')) $user->password = $request->input('password');


        if ($files = $request->file('image')) {
            foreach ($files as $file) {
                $imageName = $file->getClientOriginalName() . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $imageName);
                $user->image = $imageName;
            }
        }

        if ($user->update()) {
            unset($user->password);
            return Response::json(
                $user,
                200
            );
        } else {
            return Response::json(['error' => 'Error updating profile'], 201);
        }
    } else {
        return Response::json(['error' => 'User not found'], 404);
    }
});


Route::post('/addRestaurant', function (Request $request) {

    $restaurant = Restaurant::where('email', '=', $request->input('email'))->first();
    if ($restaurant) {
        return Response::json(['error' => 'restaurant already exist'], 201);
    } else {
        $restaurant = new Restaurant();
        $restaurant->name = $request->input('name');
        $restaurant->contactName = $request->input('contactName');
        $restaurant->email = $request->input('email');
        $restaurant->phone = $request->input('phone');
        $restaurant->address = $request->input('address');
        $restaurant->tableNumbers = $request->input('tableNumbers');
        $restaurant->category = $request->input('category');
        $restaurant->startWork = $request->input('startWork');
        $restaurant->endWork = $request->input('endWork');
        $restaurant->service = $request->input('service');


        $restaurant->image = '';
        if ($file = $request->file('image')) {
            $imageName = $file->getClientOriginalName() . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
            $restaurant->image = $imageName;
        }

        if ($restaurant->save()) {
            return Response::json(
                $restaurant,
                200
            );
        } else {
            return Response::json(
                ['error' => 'can not save restaurant'],
                202
            );
        }
    }
});




Route::post('/editRestaurant', function (Request $request) {

    $restaurant = Restaurant::where('id', '=', $request->input('id'))->first();

    if ($restaurant) {

        if ($request->has('name')) $restaurant->name = $request->input('name');
        if ($request->has('contactName')) $restaurant->contactName = $request->input('contactName');
        if ($request->has('email')) $restaurant->email = $request->input('email');
        if ($request->has('phone')) $restaurant->phone = $request->input('phone');
        if ($request->has('address')) $restaurant->address = $request->input('address');
        if ($request->has('tableNumbers')) $restaurant->tableNumbers = $request->input('tableNumbers');
        if ($request->has('category')) $restaurant->category = $request->input('category');
        if ($request->has('startWork')) $restaurant->startWork = $request->input('startWork');
        if ($request->has('endWork')) $restaurant->endWork = $request->input('endWork');
        if ($request->has('service')) $restaurant->service = $request->input('service');

        if ($file = $request->file('image')) {
            $imageName = $file->getClientOriginalName() . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
            $restaurant->image = $imageName;
        }

        if ($restaurant->update()) {
            return Response::json(
                $restaurant,
                200
            );
        } else {
            return Response::json(
                ['error' => 'can not update restaurant'],
                202
            );
        }
    } else {
        return Response::json(['error' => 'restaurant not exist'], 404);
    }
});

Route::post('/deleteRestaurant', function (Request $request) {

    $restaurant = Restaurant::where('id', '=', $request->input('id'))->first();
    if ($restaurant->delete()) {
        return Response::json(
            $restaurant,
            200
        );
    } else {
        return Response::json(
            ['error' => 'can not delete restaurant'],
            202
        );
    }
});



Route::post('/restaurantDetails', function (Request $request) {

    $restaurant = Restaurant::where('id', '=', $request->input('id'))->first();
    $favNumber = Favorate::where('type' , '=' , 'restaurant')->where('favId' , '=' , $restaurant->id)->get();
    $restaurant->favorate = sizeof($favNumber);
    $restaurant->rate = $restaurant->favorate * 5 / 100;
    if($restaurant->rate > 100) $restaurant->rate = 5;

    return Response::json(
        $restaurant,
        200
    );
});


Route::post('/category', function (Request $request) {

    $restaurants = Restaurant::where('category', '=', $request->input('category'))->get();

    return Response::json(
        $restaurants,
        200
    );
});






Route::post('/addEmployee', function (Request $request) {

    $employee = Employee::where('email', '=', $request->input('email'))->first();
    if ($employee) {
        return Response::json(['error' => 'restaurant already exist'], 201);
    } else {
        $employee = new Employee();
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->phone = $request->input('phone');
        $employee->job = $request->input('job');
        $employee->cardId = $request->input('cardId');
        $employee->restaurantId = $request->input('restaurantId');


        if ($employee->save()) {
            return Response::json(
                $employee,
                200
            );
        } else {
            return Response::json(
                ['error' => 'can not save restaurant'],
                202
            );
        }
    }
});


Route::post('/editEmployee', function (Request $request) {

    $employee = Employee::where('id', '=', $request->input('id'))->first();


    if ($employee) {

        if ($request->has('name')) $employee->name = $request->input('name');
        if ($request->has('email')) $employee->email = $request->input('email');
        if ($request->has('phone')) $employee->phone = $request->input('phone');
        if ($request->has('job')) $employee->job = $request->input('job');
        if ($request->has('id')) $employee->id = $request->input('id');
        if ($request->has('restaurantId')) $employee->restaurantId = $request->input('restaurantId');

        if ($employee->update()) {
            return Response::json(
                $employee,
                200
            );
        } else {
            return Response::json(
                ['error' => 'can not update restaurant'],
                202
            );
        }
    } else {
        return Response::json(['error' => 'employee not exist'], 404);
    }
});

Route::post('/deleteEmployee', function (Request $request) {

    $employee = Food::where('id', '=', $request->input('id'))->first();
    if ($employee->delete()) {
        return Response::json(
            $employee,
            200
        );
    } else {
        return Response::json(
            ['error' => 'can not delete employee'],
            202
        );
    }
});

Route::post('/addFood', function (Request $request) {

    $food = new Food();
    $food->name = $request->input('name');
    $food->category = $request->input('category');
    $food->price = $request->input('price');
    $food->calories = $request->input('calories');
    $food->fat = $request->input('fat');
    $food->carbs = $request->input('carbs');

    $food->protein = $request->input('protein');
    $food->label = $request->input('label');
    $food->calories = $request->input('calories');
    $food->restaurantId = $request->input('restaurantId');

    $food->image = '';
    if ($file = $request->file('image')) {
        $imageName = $file->getClientOriginalName() . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $imageName);
        $food->image = $imageName;
    }

    if ($food->save()) {
        return Response::json(
            $food,
            200
        );
    } else {
        return Response::json(
            ['error' => 'can not save Food Item'],
            202
        );
    }
});


Route::post('/editFood', function (Request $request) {

    $food = Food::where('id', '=', $request->input('id'))->first();

    if ($food) {

        if ($request->has('name')) $food->name = $request->input('name');
        if ($request->has('category')) $food->category = $request->input('category');
        if ($request->has('price')) $food->price = $request->input('price');
        if ($request->has('calories')) $food->calories = $request->input('calories');
        if ($request->has('fat')) $food->fat = $request->input('fat');
        if ($request->has('carbs')) $food->carbs = $request->input('carbs');
        if ($request->has('protein')) $food->protein = $request->input('protein');
        if ($request->has('label')) $food->label = $request->input('label');
        if ($request->has('restaurantId')) $food->restaurantId = $request->input('restaurantId');

        if ($file = $request->file('image')) {
            $imageName = $file->getClientOriginalName() . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
            $food->image = $imageName;
        }

        if ($food->update()) {
            return Response::json(
                $food,
                200
            );
        } else {
            return Response::json(
                ['error' => 'can not update restaurant'],
                202
            );
        }
    } else {
        return Response::json(['error' => 'restaurant not exist'], 404);
    }
});


Route::post('/deleteFood', function (Request $request) {

    $food = Food::where('id', '=', $request->input('id'))->first();
    if ($food->delete()) {
        return Response::json(
            $food,
            200
        );
    } else {
        return Response::json(
            ['error' => 'can not delete Food'],
            202
        );
    }
});



Route::post('/foods', function (Request $request) {

    $food = Food::where('restaurantId', '=', $request->input('id'))->get();

    return Response::json(
        $food,
        200
    );
});


Route::post('/foodDetails', function (Request $request) {

    $food = Food::where('id', '=', $request->input('id'))->first();

    return Response::json(
        $food,
        200
    );
});











Route::post('/makeReservation', function (Request $request) {

    $reservation = new Reservation();
    $reservation->userId = $request->input('userId');
    $reservation->restaurantId = $request->input('restaurantId');
    $reservation->numberOfSeats = $request->input('numberOfSeats');
    $reservation->date = $request->input('date');
    $reservation->time = $request->input('time');
    $reservation->status = 0;

    
        if ($reservation->save()) {
            return Response::json(
                $reservation,
                200
            );
        } else {
            return Response::json(
                ['error' => 'can not add reservation'],
                202
            );
        }
    
});


Route::post('/cancelReservation', function (Request $request) {

    $reservation = Reservation::where('id', '=', $request->input('id'))->first();
    $reservation->status = 1;

    if ($reservation->update()) {
        return Response::json(
            $reservation,
            200
        );
    } else {
        return Response::json(
            ['error' => 'can not update restaurant'],
            202
        );
    }
});


Route::post('/reservations', function (Request $request) {

    $reservations = Reservation::where('userId', '=', $request->input('id'))->where('status', '=', 0)->get();

    return Response::json(
        $reservations,
        200
    );
});


Route::post('/reservationDetails', function (Request $request) {

    $reservation = Reservation::where('id', '=', $request->input('id'))->first();

    return Response::json(
        $reservation,
        200
    );
});



Route::post('/addFavorate', function (Request $request) {

    $fav = new Favorate();
    $fav->userId = $request->input('userId');
    $fav->favId = $request->input('favId');
    $fav->type = $request->input('type');

    if ($fav->save()) {

        return Response::json(
            $fav,
            200
        );
    } else {
        return Response::json(
            ['error' => 'can not add favorate'],
            202
        );
    }
});


Route::post('/deleteFavorate', function (Request $request) {

    $favId = $request->input('id');
    $fav = Favorate::where('id', '=', $favId)->first();
    if ($fav->delete()) {

        return Response::json(
            $fav,
            200
        );
    } else {
        return Response::json(
            ['error' => 'can not delete favorate'],
            202
        );
    }
});

Route::post('/favorates', function (Request $request) {

    $userId = $request->input('id');
    $fav = Favorate::where('userId', '=', $userId)->get();
    $res = [];
    $i = 0;
    foreach ($fav as $f) {
        if ($f->type == "restaurant") {
            $item = Restaurant::where('id', '=', $f->favId)->first();
            $res[$i] = $item;
            $i = $i + 1;
        } else {
            $item = Food::where('id', '=', $f->favId)->first();
            $res[$i] = $item;
            $i = $i + 1;
        }
    }

    return Response::json(
        $res,
        200
    );
});


Route::post('/addCart', function (Request $request) {

    $cart = new Cart();
    $cart->userId = $request->input('userId');
    $cart->itemId = $request->input('itemId');

    if ($cart->save()) {

        return Response::json(
            $cart,
            200
        );
    } else {
        return Response::json(
            ['error' => 'can not add favorate'],
            202
        );
    }
});


Route::post('/deleteCart', function (Request $request) {

    $itemId = $request->input('id');
    $cart = Cart::where('id', '=', $itemId)->first();
    if ($cart->delete()) {

        return Response::json(
            $cart,
            200
        );
    } else {
        return Response::json(
            ['error' => 'can not delete favorate'],
            202
        );
    }
});

Route::post('/carts', function (Request $request) {

    $userId = $request->input('id');
    $cart = Cart::where('userId', '=', $userId)->get();
    $res = [];
    $i = 0;
    foreach ($cart as $f) {

        $item = Food::where('id', '=', $f->itemId)->first();
        $res[$i] = $item;
        $i = $i + 1;
    }

    return Response::json(
        $res,
        200
    );
});




Route::post('/search', function (Request $request) {

    $search = $request->input('search');
    $res = Restaurant::where('name', 'LIKE', '%' . $search . '%')->orwhere('name', 'LIKE', '%' . $search . '%')->get();

    return Response::json(
        $res,
        200
    );
});
