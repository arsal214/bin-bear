<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bin Bear - Smart Waste Management</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <style>
            /* Base styles */
            body {
                font-family: 'Figtree', sans-serif;
                margin: 0;
                padding: 0;
                background: linear-gradient(135deg, #f6f8fa 0%, #e9ecef 100%);
                min-height: 100vh;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 2rem;
            }

            /* Header styles */
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem 0;
            }

            .logo {
                font-size: 2rem;
                font-weight: bold;
                color: #2d3748;
                text-decoration: none;
            }

            .nav-links {
                display: flex;
                gap: 2rem;
            }

            .nav-link {
                color: #4a5568;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s ease;
            }

            .nav-link:hover {
                color: #2d3748;
            }

            /* Hero section */
            .hero {
                text-align: center;
                padding: 4rem 0;
            }

            .hero h1 {
                font-size: 3.5rem;
                color: #2d3748;
                margin-bottom: 1.5rem;
            }

            .hero p {
                font-size: 1.25rem;
                color: #4a5568;
                max-width: 600px;
                margin: 0 auto 2rem;
                line-height: 1.6;
            }

            /* Features section */
            .features {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                padding: 4rem 0;
            }

            .feature-card {
                background: white;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }

            .feature-card:hover {
                transform: translateY(-5px);
            }

            .feature-icon {
                width: 64px;
                height: 64px;
                margin-bottom: 1.5rem;
                color: #4299e1;
            }

            .feature-card h3 {
                font-size: 1.5rem;
                color: #2d3748;
                margin-bottom: 1rem;
            }

            .feature-card p {
                color: #4a5568;
                line-height: 1.6;
            }

            /* CTA section */
            .cta {
                text-align: center;
                padding: 4rem 0;
            }

            .cta-button {
                display: inline-block;
                background: #4299e1;
                color: white;
                padding: 1rem 2rem;
                border-radius: 0.5rem;
                text-decoration: none;
                font-weight: 600;
                transition: background-color 0.3s ease;
            }

            .cta-button:hover {
                background: #3182ce;
            }

            /* Footer */
            .footer {
                text-align: center;
                padding: 2rem 0;
                color: #4a5568;
                border-top: 1px solid #e2e8f0;
                margin-top: 4rem;
            }

            /* Responsive design */
            @media (max-width: 768px) {
                .hero h1 {
                    font-size: 2.5rem;
                }

                .nav-links {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header class="header">
                <a href="/" class="logo">Bin Bear</a>
                <nav class="nav-links">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/home') }}" class="nav-link">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="nav-link">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="nav-link">Register</a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </header>

            <section class="hero">
                <h1>Admin Panel</h1>
                <p>Access the powerful Bin Bear admin panel to manage users, monitor bin statuses, configure collection schedules, and analyze waste management data. The admin dashboard provides all the tools you need to efficiently oversee and optimize your operations.</p>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="cta-button">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="cta-button">Go To Login</a>
                    @endauth
                @endif
            </section>

            <section class="features">
                <div class="feature-card">
                    <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <h3>Real-time Monitoring</h3>
                    <p>Track bin levels and collection schedules in real-time with our advanced monitoring system.</p>
                </div>

                <div class="feature-card">
                    <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <h3>Efficient Collection</h3>
                    <p>Optimize collection routes and schedules based on real-time data and analytics.</p>
                </div>

                <div class="feature-card">
                    <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h3>Data Analytics</h3>
                    <p>Make informed decisions with comprehensive waste management analytics and reports.</p>
                </div>
            </section>

            <section class="cta">
                <h2>Ready to Transform Your Waste Management?</h2>
                <p>Join the smart waste management revolution today.</p>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="cta-button">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="cta-button">Get Started Now</a>
                    @endauth
                @endif
            </section>

            <footer class="footer">
                <p>&copy; {{ date('Y') }} Bin Bear. All rights reserved.</p>
            </footer>
        </div>
    </body>
</html>
