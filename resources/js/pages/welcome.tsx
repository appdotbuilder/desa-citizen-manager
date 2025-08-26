import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Welcome">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
                {/* Navigation */}
                <header className="w-full px-6 py-4">
                    <nav className="flex items-center justify-between max-w-7xl mx-auto">
                        <div className="flex items-center space-x-2">
                            <div className="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                <span className="text-white font-bold text-xl">🏛️</span>
                            </div>
                            <span className="text-xl font-bold text-gray-800 dark:text-white">DesaDigital</span>
                        </div>
                        
                        <div className="flex items-center space-x-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg"
                                >
                                    <span className="mr-2">📊</span>
                                    Dashboard
                                </Link>
                            ) : (
                                <div className="flex items-center space-x-3">
                                    <Link
                                        href={route('login')}
                                        className="inline-flex items-center px-4 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors dark:text-gray-300 dark:hover:text-blue-400"
                                    >
                                        Masuk
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg"
                                    >
                                        Daftar Sekarang
                                    </Link>
                                </div>
                            )}
                        </div>
                    </nav>
                </header>

                {/* Hero Section */}
                <main className="max-w-7xl mx-auto px-6 py-12">
                    <div className="text-center mb-16">
                        <h1 className="text-5xl font-bold text-gray-900 dark:text-white mb-6">
                            🏡 <span className="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Sistem Manajemen Desa Digital</span>
                        </h1>
                        <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                            Solusi komprehensif untuk pengelolaan data warga, administrasi surat, dan pelayanan desa berbasis teknologi modern. 
                            Tingkatkan efisiensi pelayanan publik dengan sistem terintegrasi.
                        </p>
                        
                        {!auth.user && (
                            <div className="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all transform hover:scale-105 shadow-lg"
                                >
                                    <span className="mr-2">🚀</span>
                                    Mulai Gratis
                                </Link>
                                <Link
                                    href={route('login')}
                                    className="inline-flex items-center px-8 py-4 border-2 border-blue-600 text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition-all dark:hover:bg-gray-800"
                                >
                                    <span className="mr-2">👋</span>
                                    Sudah Punya Akun?
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Features Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <div className="text-4xl mb-4">👥</div>
                            <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">Manajemen Warga</h3>
                            <p className="text-gray-600 dark:text-gray-300">Kelola data warga lengkap dengan NIK, KK, alamat RT/RW, pekerjaan, dan pendidikan. Dilengkapi fitur pencarian dan filter canggih.</p>
                        </div>

                        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <div className="text-4xl mb-4">🏠</div>
                            <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">RT/RW & Dusun</h3>
                            <p className="text-gray-600 dark:text-gray-300">Organisasi wilayah administratif dengan pengelolaan Ketua RT/RW. Statistik populasi per wilayah tersedia real-time.</p>
                        </div>

                        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <div className="text-4xl mb-4">📋</div>
                            <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">Surat Administratif</h3>
                            <p className="text-gray-600 dark:text-gray-300">Sistem permohonan surat online dan manual dengan workflow approval RT → RW → Admin → Kepala Desa. Tracking status real-time.</p>
                        </div>

                        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <div className="text-4xl mb-4">📰</div>
                            <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">Berita & Pengumuman</h3>
                            <p className="text-gray-600 dark:text-gray-300">Publikasi informasi desa, pengumuman, kegiatan, dan peraturan dengan sistem persetujuan berlapis.</p>
                        </div>

                        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <div className="text-4xl mb-4">📸</div>
                            <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">Galeri & Dokumentasi</h3>
                            <p className="text-gray-600 dark:text-gray-300">Dokumentasi kegiatan desa, fasilitas, dan proyek pembangunan dengan kategorisasi dan sistem tag.</p>
                        </div>

                        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <div className="text-4xl mb-4">📊</div>
                            <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">Laporan & Statistik</h3>
                            <p className="text-gray-600 dark:text-gray-300">Dashboard analitik dengan demografi penduduk, statistik surat, dan laporan ekspor PDF/Excel.</p>
                        </div>
                    </div>

                    {/* Role-Based Access */}
                    <div className="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg mb-16">
                        <h2 className="text-3xl font-bold text-center text-gray-900 dark:text-white mb-8">
                            🔐 Sistem Akses Berbasis Peran
                        </h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div className="text-center p-4">
                                <div className="text-3xl mb-2">👑</div>
                                <h4 className="font-bold text-gray-900 dark:text-white mb-1">Super Admin</h4>
                                <p className="text-sm text-gray-600 dark:text-gray-300">Manajemen global semua desa dan paket berlangganan</p>
                            </div>
                            <div className="text-center p-4">
                                <div className="text-3xl mb-2">🏛️</div>
                                <h4 className="font-bold text-gray-900 dark:text-white mb-1">Admin Desa</h4>
                                <p className="text-sm text-gray-600 dark:text-gray-300">Kelola data warga, berita, galeri, dan input surat manual</p>
                            </div>
                            <div className="text-center p-4">
                                <div className="text-3xl mb-2">🎖️</div>
                                <h4 className="font-bold text-gray-900 dark:text-white mb-1">Kepala Desa</h4>
                                <p className="text-sm text-gray-600 dark:text-gray-300">Persetujuan final surat dan monitoring statistik desa</p>
                            </div>
                            <div className="text-center p-4">
                                <div className="text-3xl mb-2">🏠</div>
                                <h4 className="font-bold text-gray-900 dark:text-white mb-1">Ketua RW</h4>
                                <p className="text-sm text-gray-600 dark:text-gray-300">Verifikasi surat dan kelola data warga di wilayah RW</p>
                            </div>
                            <div className="text-center p-4">
                                <div className="text-3xl mb-2">🏘️</div>
                                <h4 className="font-bold text-gray-900 dark:text-white mb-1">Ketua RT</h4>
                                <p className="text-sm text-gray-600 dark:text-gray-300">Verifikasi surat dan kelola data warga di wilayah RT</p>
                            </div>
                            <div className="text-center p-4">
                                <div className="text-3xl mb-2">👨‍👩‍👧‍👦</div>
                                <h4 className="font-bold text-gray-900 dark:text-white mb-1">Warga</h4>
                                <p className="text-sm text-gray-600 dark:text-gray-300">Ajukan surat online dan pantau status permohonan</p>
                            </div>
                        </div>
                    </div>

                    {/* Multi-Tenant SaaS */}
                    <div className="text-center bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-8 text-white">
                        <h2 className="text-3xl font-bold mb-4">☁️ Solusi Multi-Tenant SaaS</h2>
                        <p className="text-xl mb-6 max-w-3xl mx-auto">
                            Sistem cloud yang dapat mengelola banyak desa dengan isolasi data ketat. 
                            Paket berlangganan fleksibel dengan integrasi payment gateway untuk berbagai kebutuhan.
                        </p>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                            <div className="bg-white/20 rounded-lg p-4">
                                <h4 className="font-bold mb-2">🛡️ Keamanan Data</h4>
                                <p className="text-sm">Isolasi data per desa dengan enkripsi tingkat enterprise</p>
                            </div>
                            <div className="bg-white/20 rounded-lg p-4">
                                <h4 className="font-bold mb-2">📱 Cloud Storage</h4>
                                <p className="text-sm">Media dan file tersimpan aman di cloud dengan folder terpisah per desa</p>
                            </div>
                            <div className="bg-white/20 rounded-lg p-4">
                                <h4 className="font-bold mb-2">💳 Payment Integration</h4>
                                <p className="text-sm">Sistem pembayaran otomatis untuk berlangganan dan trial</p>
                            </div>
                        </div>
                    </div>
                </main>

                {/* Footer */}
                <footer className="mt-16 py-8 border-t border-gray-200 dark:border-gray-700">
                    <div className="max-w-7xl mx-auto px-6 text-center">
                        <p className="text-gray-600 dark:text-gray-400">
                            Built with ❤️ for Indonesian Villages •{' '}
                            <a 
                                href="https://app.build" 
                                target="_blank" 
                                className="text-blue-600 hover:underline dark:text-blue-400"
                            >
                                Powered by app.build
                            </a>
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}