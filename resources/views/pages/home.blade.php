@extends('layouts.app', ['title' => "Welcome"])

@section('content')
 <!-- Hero Section -->
 <section class="hero-section text-center">
    {{-- <div class="overlay"></div> --}}
    <div class="container my-auto">
        <h1>Time Table Generator Software</h1>
        <p class="lead">Effortlessly create and manage your schedules</p>
        <a href="{{ route('upload.first') }}" class="btn btn-lg btn-success">Generate Time Table</a>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h2>About Our Software</h2>
                <p>Welcome to our Time Table Generator Software! Our software is designed to simplify the process of creating and managing schedules for various purposes, whether it's for schools, universities, organizations, or events.</p>
                <p>With our intuitive interface and powerful features, you can easily create custom schedules tailored to your specific needs. Whether you need to schedule classes, meetings, exams, or events, our software provides the flexibility and functionality to make it happen efficiently.</p>
                <p>Our software offers features such as:</p>
                <ul>
                    <li>Easy-to-use interface for creating schedules</li>
                    <li>Flexible scheduling options to accommodate different requirements</li>
                    <li>Customizable templates for quick schedule creation</li>
                    <li>Automated conflict detection and resolution</li>
                    <li>Integration with calendar apps for seamless scheduling</li>
                </ul>
                <p>No more manual scheduling headaches or missed appointments. Our Time Table Generator Software streamlines the scheduling process, saving you time and ensuring that your schedules are always organized and up-to-date.</p>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://cdn.pixabay.com/photo/2017/05/20/00/26/professor-2327957_1280.jpg" width="70%" alt="About Image" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Reasons to Use Section -->
<section class="reasons-section text-center">
    <div class="container">
        <h2 class="my-3 mb-5">Why Use Our Software?</h2>
        <div class="row px-5">
            <div class="col-lg-3 my-why mx-auto">
                <h3 class="green-color">Easy to Use</h3>
                <i class="bi bi-check2-circle my-i-font"></i>
                <p>Our software features an intuitive and user-friendly interface, making it easy for anyone to create and manage schedules effortlessly.</p>
            </div>
            <div class="col-lg-3 my-why mx-auto">
                <h3 class="green-color">Flexible Scheduling</h3>
                <i class="bi bi-calendar-event my-i-font"></i>
                <p>Enjoy the flexibility of our scheduling options, allowing you to customize schedules to fit your unique requirements and preferences.</p>
            </div>
            <div class="col-lg-3 my-why mx-auto">
                <h3 class="green-color">Time Saving</h3>
                <i class="bi bi-stopwatch my-i-font"></i>
                <p>Save time and streamline your scheduling process with our software's automated features, helping you create schedules quickly and efficiently.</p>
            </div>
        </div>
    </div>
</section>

<!-- Additional Information Section -->
<section id="additional-information" class="additional-information-section bg-white py-5">
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-center">Additional Information</h2>
            <p>Here are some additional details about our Time Table Generator Software:</p>
            <ul>
                <li><strong>Support:</strong> Our dedicated support team is available to assist you with any questions or issues you may encounter.</li>
                <li><strong>Updates:</strong> We regularly update our software to add new features, improve performance, and address any bugs or issues.</li>
                <li><strong>Compatibility:</strong> Our software is compatible with all modern web browsers and devices, ensuring a seamless experience for all users.</li>
                <li><strong>Security:</strong> We prioritize the security of your data and use advanced encryption techniques to keep your information safe and secure.</li>
                <li><strong>Feedback:</strong> We welcome your feedback and suggestions for improving our software. Your input helps us enhance the user experience and meet the needs of our users.</li>
            </ul>
            <p>For more information or to get started with our Time Table Generator Software, feel free to <a href="#contact">contact us</a> or sign up for a free trial!</p>
        </div>
    </div>
</div>
</section>
@endsection

