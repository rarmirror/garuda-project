<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FlightSearchController;
use App\Http\Controllers\FlightBookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyBookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HotelBookingController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/flights', [FlightSearchController::class, 'index'])->name('flights.search');

Route::get('/flight/{flight}/choose-tier', [FlightBookingController::class, 'chooseTier'])->name('flight.choose-tier');
Route::get('/flight/booking/{flight}/choose-seat', [FlightBookingController::class, 'chooseSeat'])->name('flight.choose-seat');
Route::post('/flight/booking/{flight}/choose-seat', [FlightBookingController::class, 'storeSeats'])->name('flight.store-seats');

Route::get('/booking/passenger-form', [FlightBookingController::class, 'passengerForm'])->name('booking.passenger-form');
Route::post('/booking/passenger-form', [FlightBookingController::class, 'storeTransaction'])->name('booking.store-transaction');

Route::get('/booking/payment/{transaction}', [FlightBookingController::class, 'payment'])->name('booking.payment');
Route::get('/booking/success/{transaction}', [FlightBookingController::class, 'success'])->name('booking.success');
Route::post('/booking/callback', [FlightBookingController::class, 'callback'])->name('booking.callback');

Route::get('/my-booking', [MyBookingController::class, 'index'])->name('my-booking.index');
Route::post('/my-booking', [MyBookingController::class, 'search'])->name('my-booking.search');
Route::get('/my-booking/{transaction}/download', [MyBookingController::class, 'downloadPdf'])->name('my-booking.download');
Route::post('/my-booking/{transaction}/cancel', [MyBookingController::class, 'cancel'])->name('my-booking.cancel');

// Schedule
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

// Call Us
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Testimonials
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');

// Hotels Frontend
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{slug}', [HotelController::class, 'show'])->name('hotels.show');

// Hotel Booking
Route::get('/hotel-booking/{room}', [HotelBookingController::class, 'bookingForm'])->name('hotel.booking.form');
Route::post('/hotel-booking/{room}', [HotelBookingController::class, 'storeBooking'])->name('hotel.booking.store');
Route::get('/hotel-booking/payment/{booking}', [HotelBookingController::class, 'payment'])->name('hotel.booking.payment');
Route::get('/hotel-booking/success/{booking}', [HotelBookingController::class, 'success'])->name('hotel.booking.success');
Route::post('/hotel-booking/callback', [HotelBookingController::class, 'callback'])->name('hotel.booking.callback');

// My Hotel Booking
Route::get('/my-booking/hotel', [MyBookingController::class, 'hotelSearch'])->name('my-booking.hotel.search');
Route::post('/my-booking/hotel', [MyBookingController::class, 'hotelResult'])->name('my-booking.hotel.result');
Route::get('/my-booking/hotel/{booking}/download', [MyBookingController::class, 'downloadHotelPdf'])->name('my-booking.hotel.download');
Route::post('/my-booking/hotel/{booking}/cancel', [MyBookingController::class, 'cancelHotel'])->name('my-booking.hotel.cancel');