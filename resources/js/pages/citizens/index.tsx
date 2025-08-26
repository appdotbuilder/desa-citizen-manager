import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Link, router } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import { type BreadcrumbItem } from '@/types';
import { useState } from 'react';

interface Citizen {
    id: number;
    name: string;
    nik: string;
    email: string;
    phone: string;
    gender: string;
    age: number;
    occupation: string;
    citizen_status: string;
    rt: { number: string; rw: { number: string } } | null;
    rw: { number: string } | null;
}

interface CitizensIndexProps {
    citizens: {
        data: Citizen[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    filters: {
        search?: string;
        rt_id?: string;
        rw_id?: string;
        gender?: string;
        citizen_status?: string;
    };
    filter_options: {
        rts: Array<{ id: number; number: string; rw_id: number }>;
        rws: Array<{ id: number; number: string }>;
    };
    [key: string]: unknown;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Data Warga', href: '/citizens' },
];

export default function CitizensIndex({ citizens, filters, filter_options }: CitizensIndexProps) {
    const [search, setSearch] = useState(filters.search || '');
    const [selectedRw, setSelectedRw] = useState(filters.rw_id || '');
    const [selectedRt, setSelectedRt] = useState(filters.rt_id || '');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/citizens', { 
            search: search || undefined,
            rw_id: selectedRw || undefined,
            rt_id: selectedRt || undefined,
        }, { preserveState: true });
    };

    const getStatusBadge = (status: string) => {
        const badges = {
            active: 'bg-green-100 text-green-800',
            inactive: 'bg-gray-100 text-gray-800',
            moved: 'bg-yellow-100 text-yellow-800',
            deceased: 'bg-red-100 text-red-800',
        };
        
        const labels = {
            active: 'Aktif',
            inactive: 'Tidak Aktif',
            moved: 'Pindah',
            deceased: 'Meninggal',
        };

        return (
            <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${badges[status as keyof typeof badges] || badges.active}`}>
                {labels[status as keyof typeof labels] || status}
            </span>
        );
    };

    const availableRts = filter_options.rts.filter(rt => 
        !selectedRw || rt.rw_id.toString() === selectedRw
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Data Warga" />
            <div className="p-6">
                <div className="flex justify-between items-center mb-6">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">👥 Data Warga</h1>
                        <p className="text-gray-600">Kelola data warga desa</p>
                    </div>
                    <Link href="/citizens/create">
                        <Button className="bg-blue-600 hover:bg-blue-700">
                            <span className="mr-2">➕</span>
                            Tambah Warga
                        </Button>
                    </Link>
                </div>

                {/* Filters */}
                <div className="bg-white rounded-lg shadow-sm border p-4 mb-6">
                    <form onSubmit={handleSearch} className="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <Input
                                type="text"
                                placeholder="Cari nama, NIK, email..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full"
                            />
                        </div>
                        
                        <div>
                            <select
                                value={selectedRw}
                                onChange={(e) => {
                                    setSelectedRw(e.target.value);
                                    setSelectedRt(''); // Reset RT when RW changes
                                }}
                                className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            >
                                <option value="">Semua RW</option>
                                {filter_options.rws.map(rw => (
                                    <option key={rw.id} value={rw.id}>RW {rw.number}</option>
                                ))}
                            </select>
                        </div>
                        
                        <div>
                            <select
                                value={selectedRt}
                                onChange={(e) => setSelectedRt(e.target.value)}
                                className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                                disabled={!selectedRw}
                            >
                                <option value="">Semua RT</option>
                                {availableRts.map(rt => (
                                    <option key={rt.id} value={rt.id}>RT {rt.number}</option>
                                ))}
                            </select>
                        </div>
                        
                        <div>
                            <select
                                defaultValue={filters.gender || ''}
                                className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                                name="gender"
                            >
                                <option value="">Semua Gender</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        
                        <div>
                            <Button type="submit" className="w-full">
                                🔍 Cari
                            </Button>
                        </div>
                    </form>
                </div>

                {/* Table */}
                <div className="bg-white rounded-lg shadow-sm border overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Warga
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIK
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        RT/RW
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Gender/Usia
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pekerjaan
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {citizens.data.map((citizen) => (
                                    <tr key={citizen.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div className="text-sm font-medium text-gray-900">
                                                    {citizen.name}
                                                </div>
                                                <div className="text-sm text-gray-500">
                                                    {citizen.email}
                                                </div>
                                                {citizen.phone && (
                                                    <div className="text-sm text-gray-500">
                                                        📞 {citizen.phone}
                                                    </div>
                                                )}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {citizen.nik}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {citizen.rt && citizen.rt.rw ? 
                                                `RT ${citizen.rt.number}/RW ${citizen.rt.rw.number}` : 
                                                '-'
                                            }
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>
                                                <span className="mr-2">
                                                    {citizen.gender === 'L' ? '👨' : '👩'}
                                                </span>
                                                {citizen.gender === 'L' ? 'Laki-laki' : 'Perempuan'}
                                            </div>
                                            {citizen.age && (
                                                <div className="text-gray-500">
                                                    {citizen.age} tahun
                                                </div>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {citizen.occupation || '-'}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            {getStatusBadge(citizen.citizen_status)}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <Link 
                                                href={`/citizens/${citizen.id}`}
                                                className="text-blue-600 hover:text-blue-900"
                                            >
                                                👁️ Lihat
                                            </Link>
                                            <Link 
                                                href={`/citizens/${citizen.id}/edit`}
                                                className="text-indigo-600 hover:text-indigo-900"
                                            >
                                                ✏️ Edit
                                            </Link>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {/* Empty State */}
                    {citizens.data.length === 0 && (
                        <div className="text-center py-12">
                            <div className="text-4xl mb-4">👥</div>
                            <h3 className="text-lg font-medium text-gray-900 mb-2">Belum ada data warga</h3>
                            <p className="text-gray-500 mb-4">Mulai dengan menambahkan data warga pertama</p>
                            <Link href="/citizens/create">
                                <Button>Tambah Warga</Button>
                            </Link>
                        </div>
                    )}
                </div>

                {/* Pagination */}
                {citizens.data.length > 0 && (
                    <div className="mt-6 flex items-center justify-between">
                        <div className="flex items-center space-x-2">
                            {citizens.prev_page_url && (
                                <Link href={citizens.prev_page_url}>
                                    <Button variant="outline">← Sebelumnya</Button>
                                </Link>
                            )}
                            {citizens.next_page_url && (
                                <Link href={citizens.next_page_url}>
                                    <Button variant="outline">Selanjutnya →</Button>
                                </Link>
                            )}
                        </div>
                        <div className="text-sm text-gray-700">
                            Menampilkan {citizens.data.length} dari total warga
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}