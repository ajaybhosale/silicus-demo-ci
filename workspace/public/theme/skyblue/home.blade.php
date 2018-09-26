@extends($theme.'.layouts.app')
@section('content')
@include('Slider::view_slider',['imagesList'=>$imagesList])
<section id="feature" >
    <div class="container">
        <div class="center wow fadeInDown">
            <h2>Transportation</h2>
        </div>

        <div class="row">
            <div class="features">
                <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                    <div class="feature-wrap">
                        <i class="fa fa-plane"></i>
                        <h2>Aviation</h2>
                        <h3>Innovation in aviation didn’t stop with the Wright Brothers. From the busiest airports to the most classified defense facilities, we’re advancing technology and improving safety, security and quality for air passenger terminals, terminal access runways, taxiways, aprons, airport hangars and more.</h3>
                    </div>
                </div><!--/.col-md-4-->

                <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                    <div class="feature-wrap">
                        <i class="fa fa-exchange"></i>
                        <h2>Bridges</h2>
                        <h3>From remote single-span to iconic landmarks, bridges are engineering marvels of the modern world. With experience spanning from coast to coast, we offer cable-stayed, tied-arch, beam, truss and suspension bridge construction. Many of our projects are alternative procurement, design-build and P-3 methods, making it easier to fund.</h3>
                    </div>
                </div><!--/.col-md-4-->


                <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                    <div class="feature-wrap">
                        <i class="fa fa-road"></i>
                        <h2>Roads</h2>
                        <h3>From rural roads through Yosemite Valley to high-profile highway expansions, we’re experts in advancing roadway systems across the country. Solutions include reducing traffic congestion, making roads and intersections safer to the traveling public, improving long-term maintenance and reducing environmental impacts that extend the lifecycle of transportation infrastructure.Learn More</h3>
                    </div>
                </div><!--/.col-md-4-->
                <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                    <div class="feature-wrap">
                        <i class="fa fa-terminal"></i>
                        <h2>Ports</h2>
                        <h3>From ship to port, we expertly navigate through coastal construction and shoreline protection, including bank stabilization, piers, wharves and environmental protection services.</h3>
                    </div>
                </div><!--/.col-md-4-->

                <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                    <div class="feature-wrap">
                        <i class="fa fa-ship"></i>
                        <h2>Marine</h2>
                        <h3>From ship to port, we expertly navigate through coastal construction and shoreline protection, including bank stabilization, piers, wharves and environmental protection services.</h3>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                    <div class="feature-wrap">
                        <i class="fa fa-train"></i>
                        <h2>Mass Transit</h2>
                        <h3>Come along for the ride. From Portland’s light rail to the Tucson Modern Streetcar and the Las Vegas Monorail, public transit continues to spur economic growth, revitalize downtowns and offer sustainable alternatives to travelers. In the last two decades alone, we have completed more than eight billion in transit rail and multimodal facilities.</h3>
                    </div>
                </div><!--/.col-md-4-->

                <!--/.col-md-4-->
            </div><!--/.services-->
        </div><!--/.row-->
    </div><!--/.container-->
</section><!--/#feature-->

<section id="recent-works">
    <div class="container">
        {!! ImageGallery::view() !!}
    </div><!--/.container-->
</section><!--/#recent-works-->

<section id="services" class="service-item">
    <div class="container">
        <div class="center wow fadeInDown">
            <h2>Find a Solution</h2>
            <p class="lead">We have built an enviable reputation in the consumer goods, heavy industry, high-tech, manufacturing, medical, recreational vehicle, and transportation sectors. multidisciplinary team of engineering experts.

                Who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain.

                Denouncing pleasure and praising pain was born and I will give you a complete account of the system, expound the actual teachings.</p>
        </div>
        <div class="row">
            {!! PortfolioFacade::getLatestNews(6) !!}
        </div><!--/.row-->
    </div><!--/.container-->
</section><!--/#services-->

<section id="middle">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 wow fadeInDown" style='margin: 47px 0px 0px;'>
                <div class="skill">
                    <!-- {!!Banner::render('bottom')!!} -->
                    <!-- <h2>Our Skills</h2>
                     <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

                     <div class="progress-wrap">
                         <h3>Graphic Design</h3>
                         <div class="progress">
                             <div class="progress-bar  color1" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 85%">
                                 <span class="bar-width">85%</span>
                             </div>

                         </div>
                     </div>

                     <div class="progress-wrap">
                         <h3>HTML</h3>
                         <div class="progress">
                             <div class="progress-bar color2" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 95%">
                                 <span class="bar-width">95%</span>
                             </div>
                         </div>
                     </div>

                     <div class="progress-wrap">
                         <h3>CSS</h3>
                         <div class="progress">
                             <div class="progress-bar color3" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                 <span class="bar-width">80%</span>
                             </div>
                         </div>
                     </div>

                     <div class="progress-wrap">
                         <h3>Wordpress</h3>
                         <div class="progress">
                             <div class="progress-bar color4" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
                                 <span class="bar-width">90%</span>
                             </div>
                         </div>
                     </div>-->
                </div>

            </div><!--/.col-sm-6-->

            <div class="col-sm-12 wow fadeInDown center">
                <!--<div class="accordion"> -->
                <a href="/news/viewAll"><h2>Latest News</h2></a>

                <!-- <div class="panel-group" id="accordion1"> -->
                {!! NewsFacade::getLatestNews(4) !!}
                <!--
                {!! EventsFacade::getLatestEvents(4) !!}
                -->

                <!-- </div> --><!--/#accordion1-->
                <!-- </div> -->
            </div>

        </div><!--/.row-->
    </div><!--/.container-->
</section><!--/#middle-->

<section id="content">
    <div class="container">
        <div class="row">

            <div class="col-xs-12 col-sm-4 wow fadeInDown">
                <div class="testimonial">
                    <!--<h2>Testimonials</h2>
                     <div class="media testimonial-inner">
                        <div class="pull-left">
                            <img class="img-responsive img-circle" src="{{ url('/theme') }}/{{$theme}}/images/testimonials1.png">
                        </div>
                        <div class="media-body">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt</p>
                            <span><strong>-John Doe/</strong> Director of corlate.com</span>
                        </div>
                    </div>

                    <div class="media testimonial-inner">
                        <div class="pull-left">
                            <img class="img-responsive img-circle" src="{{ url('/theme') }}/{{$theme}}/images/testimonials1.png">
                        </div>
                        <div class="media-body">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt</p>
                            <span><strong>-John Doe/</strong> Director of corlate.com</span>
                        </div>
                    </div>-->

                    {!!TestimonialsFacade::displayTestimonial()!!}
                </div>
            </div>

        </div><!--/.row-->
    </div><!--/.container-->
</section><!--/#content-->

<section id="partner">
    <div class="container">
        <div class="center wow fadeInDown">
            <h2>Our Partners</h2>
            <p class="lead">United Way works with companies, governments, nonprofits and other organizations to address complex challenges on a worldwide scale. Our partners contribute more than money. Their ideas, volunteer power, in-kind support and more are helping build stronger communities.</p>
        </div>

        <div class="partners">
            <ul>
                <li> <a href="#"><img class="img-responsive wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms" src="{{ url('/theme') }}/{{$theme}}/images/partners/partner1.png"></a></li>
                <li> <a href="#"><img class="img-responsive wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms" src="{{ url('/theme') }}/{{$theme}}/images/partners/partner2.png"></a></li>
                <li> <a href="#"><img class="img-responsive wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="900ms" src="{{ url('/theme') }}/{{$theme}}/images/partners/partner3.png"></a></li>
                <li> <a href="#"><img class="img-responsive wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="1200ms" src="{{ url('/theme') }}/{{$theme}}/images/partners/partner4.png"></a></li>
                <li> <a href="#"><img class="img-responsive wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="1500ms" src="{{ url('/theme') }}/{{$theme}}/images/partners/partner5.png"></a></li>
            </ul>
        </div>
    </div><!--/.container-->
</section><!--/#partner-->

<section id="conatcat-info">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div class="media contact-info wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                    <div class="pull-left">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div class="media-body">
                        <h2>Have a question or need a custom quote?</h2>
                        <p>Thank you for your interest in our services. Browse our online support site for quick answers, manuals and in-depth technical articles or contact our team or distributors and we will find you a solution. Phone +41 44 515 04 90   Fax +41 44 515 04 91</p>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/.container-->
</section><!--/#conatcat-info-->
@endsection
