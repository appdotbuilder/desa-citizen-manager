import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

interface DashboardProps {
    user_role: string;
    stats: Record<string, number>;
    user?: {
        id: number;
        name: string;
        desa?: { name: string };
        full_name_with_title?: string;
    };
    recent_villages?: Array<{ id: number; name: string; created_at: string }>;
    village_stats?: Array<{ id: number; name: string; users_count: number; letters_count: number }>;
    recent_letters?: Array<{ id: number; subject: string; citizen: { name: string }; letterType: { name: string }; rt: { number: string }; rw: { number: string } }>;
    demographic_stats?: {
        gender_stats?: { male: number; female: number };
        age_groups?: { children: number; adults: number; elderly: number };
        education_stats?: Record<string, number>;
        marital_status_stats?: Record<string, number>;
    };
    letter_status_stats?: Record<string, number>;
    recent_news?: Array<{ id: number; title: string; excerpt: string; published_at: string }>;
    [key: string]: unknown;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard({ 
    user_role, 
    stats, 
    user, 
    demographic_stats,
    letter_status_stats,
    recent_news
}: DashboardProps) {
    
    const renderSuperAdminDashboard = () => (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div className="bg-white rounded-xl p-6 shadow-sm border">
                <div className="flex items-center justify-between">
                    <div>
                        <p className="text-sm text-gray-500">Total Desa</p>
                        <p className="text-2xl font-bold text-gray-900">{stats.total_villages}</p>
                    </div>
                    <div className="text-3xl">🏛️</div>
                </div>
            </div>
            
            <div className="bg-white rounded-xl p-6 shadow-sm border">
                <div className="flex items-center justify-between">
                    <div>
                        <p className="text-sm text-gray-500">Desa Aktif</p>
                        <p className="text-2xl font-bold text-green-600">{stats.active_villages}</p>
                    </div>
                    <div className="text-3xl">✅</div>
                </div>
            </div>
            
            <div className="bg-white rounded-xl p-6 shadow-sm border">
                <div className="flex items-center justify-between">
                    <div>
                        <p className="text-sm text-gray-500">Total Pengguna</p>
                        <p className="text-2xl font-bold text-blue-600">{stats.total_users}</p>
                    </div>
                    <div className="text-3xl">👥</div>
                </div>
            </div>
            
            <div className="bg-white rounded-xl p-6 shadow-sm border">
                <div className="flex items-center justify-between">
                    <div>
                        <p className="text-sm text-gray-500">Total Surat</p>
                        <p className="text-2xl font-bold text-purple-600">{stats.total_letters}</p>
                    </div>
                    <div className="text-3xl">📋</div>
                </div>
            </div>
        </div>
    );

    const renderVillageDashboard = () => (
        <div className="space-y-8">
            {/* Main Stats */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div className="bg-white rounded-xl p-6 shadow-sm border">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-gray-500">Total Warga</p>
                            <p className="text-2xl font-bold text-gray-900">{stats.total_citizens}</p>
                        </div>
                        <div className="text-3xl">👥</div>
                    </div>
                </div>
                
                <div className="bg-white rounded-xl p-6 shadow-sm border">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-gray-500">Surat Proses</p>
                            <p className="text-2xl font-bold text-yellow-600">{stats.pending_letters}</p>
                        </div>
                        <div className="text-3xl">⏳</div>
                    </div>
                </div>
                
                <div className="bg-white rounded-xl p-6 shadow-sm border">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-gray-500">Surat Selesai</p>
                            <p className="text-2xl font-bold text-green-600">{stats.completed_letters}</p>
                        </div>
                        <div className="text-3xl">✅</div>
                    </div>
                </div>
                
                <div className="bg-white rounded-xl p-6 shadow-sm border">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-gray-500">Berita Aktif</p>
                            <p className="text-2xl font-bold text-blue-600">{stats.published_news}</p>
                        </div>
                        <div className="text-3xl">📰</div>
                    </div>
                </div>
            </div>

            {/* Role-specific additional stats */}
            {(user_role === 'ketua_rt' || user_role === 'ketua_rw') && (
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="bg-white rounded-xl p-6 shadow-sm border">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-500">
                                    {user_role === 'ketua_rt' ? 'Warga RT' : 'Warga RW'}
                                </p>
                                <p className="text-2xl font-bold text-gray-900">
                                    {stats.rt_citizens || stats.rw_citizens}
                                </p>
                            </div>
                            <div className="text-3xl">🏠</div>
                        </div>
                    </div>
                    
                    <div className="bg-white rounded-xl p-6 shadow-sm border">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-500">Surat Menunggu</p>
                                <p className="text-2xl font-bold text-red-600">
                                    {stats.pending_rt_letters || stats.pending_rw_letters}
                                </p>
                            </div>
                            <div className="text-3xl">📋</div>
                        </div>
                    </div>
                    
                    <div className="bg-white rounded-xl p-6 shadow-sm border">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-500">Total Surat</p>
                                <p className="text-2xl font-bold text-gray-900">
                                    {stats.rt_letters || stats.rw_letters}
                                </p>
                            </div>
                            <div className="text-3xl">📄</div>
                        </div>
                    </div>
                </div>
            )}

            {/* Demographics for Admin/Kepala Desa */}
            {(user_role === 'admin_desa' || user_role === 'kepala_desa') && demographic_stats && (
                <div className="bg-white rounded-xl p-6 shadow-sm border">
                    <h3 className="text-lg font-semibold text-gray-900 mb-4">📊 Statistik Demografi</h3>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4 className="font-medium text-gray-700 mb-2">Jenis Kelamin</h4>
                            <div className="space-y-1 text-sm">
                                <div className="flex justify-between">
                                    <span>Laki-laki:</span>
                                    <span className="font-semibold">{demographic_stats.gender_stats?.male || 0}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span>Perempuan:</span>
                                    <span className="font-semibold">{demographic_stats.gender_stats?.female || 0}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 className="font-medium text-gray-700 mb-2">Kelompok Usia</h4>
                            <div className="space-y-1 text-sm">
                                <div className="flex justify-between">
                                    <span>Anak-anak:</span>
                                    <span className="font-semibold">{demographic_stats.age_groups?.children || 0}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span>Dewasa:</span>
                                    <span className="font-semibold">{demographic_stats.age_groups?.adults || 0}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span>Lansia:</span>
                                    <span className="font-semibold">{demographic_stats.age_groups?.elderly || 0}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 className="font-medium text-gray-700 mb-2">Status Surat</h4>
                            <div className="space-y-1 text-sm">
                                {letter_status_stats && Object.entries(letter_status_stats).map(([status, count]) => (
                                    <div key={status} className="flex justify-between">
                                        <span className="capitalize">{status.replace('_', ' ')}:</span>
                                        <span className="font-semibold">{count}</span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Recent News for Citizens */}
            {user_role === 'warga' && recent_news && recent_news.length > 0 && (
                <div className="bg-white rounded-xl p-6 shadow-sm border">
                    <h3 className="text-lg font-semibold text-gray-900 mb-4">📰 Berita Terbaru</h3>
                    <div className="space-y-3">
                        {recent_news.map((news) => (
                            <div key={news.id} className="border-l-4 border-blue-500 pl-4">
                                <h4 className="font-medium text-gray-900">{news.title}</h4>
                                <p className="text-sm text-gray-600 mt-1">{news.excerpt}</p>
                                <p className="text-xs text-gray-500 mt-2">
                                    {new Date(news.published_at).toLocaleDateString('id-ID')}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            )}
        </div>
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="p-6">
                <div className="mb-6">
                    <h1 className="text-2xl font-bold text-gray-900">
                        Dashboard {user_role === 'super_admin' ? 'Super Admin' : user?.desa?.name}
                    </h1>
                    <p className="text-gray-600">
                        {user_role === 'super_admin' 
                            ? 'Kelola semua desa dan sistem secara global' 
                            : `Selamat datang, ${user?.full_name_with_title || user?.name}`
                        }
                    </p>
                </div>

                {user_role === 'super_admin' ? renderSuperAdminDashboard() : renderVillageDashboard()}
            </div>
        </AppLayout>
    );
}