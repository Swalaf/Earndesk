@extends('layouts.admin')

@section('title', 'Maintenance Mode')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Maintenance Mode</h1>
                <p class="mt-1 text-sm text-gray-500">Put the platform in maintenance mode for updates</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings') }}" class="text-indigo-600 hover:text-indigo-900">
                    ← Back to Settings
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update', 'maintenance') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Maintenance Mode Status -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Maintenance Status</h3>
                    <p class="text-sm text-gray-500">Current maintenance mode status</p>
                </div>
                <div class="px-6 py-4">
                    @php
                        $maintenanceEnabled = \App\Models\SystemSetting::isMaintenanceModeEnabled();
                    @endphp
                    <div class="flex items-center justify-between p-4 rounded-lg {{ $maintenanceEnabled ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($maintenanceEnabled)
                                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                                @else
                                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium {{ $maintenanceEnabled ? 'text-red-800' : 'text-green-800' }}">
                                    {{ $maintenanceEnabled ? 'Maintenance Mode is ENABLED' : 'System is Running Normally' }}
                                </h4>
                                <p class="text-sm {{ $maintenanceEnabled ? 'text-red-600' : 'text-green-600' }}">
                                    @if($maintenanceEnabled)
                                        The platform is currently in maintenance mode. Regular users cannot access it.
                                    @else
                                        The platform is live and accessible to all users.
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="maintenance_mode_enabled" value="true"
                                    {{ $maintenanceEnabled ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-14 h-8 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-red-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Enable</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Message -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Maintenance Message</h3>
                    <p class="text-sm text-gray-500">Custom message displayed to users during maintenance</p>
                </div>
                <div class="px-6 py-4">
                    <div>
                        <label for="maintenance_message" class="block text-sm font-medium text-gray-700">
                            Message
                        </label>
                        <div class="mt-1">
                            <textarea id="maintenance_message" name="maintenance_message" rows="3"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="We are performing scheduled maintenance. Please check back shortly.">{{ old('maintenance_message', $settingsByKey['maintenance_message'] ?? 'We are performing scheduled maintenance. Please check back shortly.') }}</textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">This message will be displayed to all users (except admins) when maintenance mode is enabled.</p>
                    </div>
                </div>
            </div>

            <!-- Admin Access -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Admin Access</h3>
                    <p class="text-sm text-gray-500">Configure admin access during maintenance mode</p>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div>
                            <h4 class="text-sm font-medium text-blue-800">Always Allow Admin Login</h4>
                            <p class="text-sm text-blue-600">Administrators can always access the admin panel even during maintenance mode.</p>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user-shield text-blue-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white {{ $maintenanceEnabled ? 'bg-green-600 hover:bg-green-700' : 'bg-indigo-600 hover:bg-indigo-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i> {{ $maintenanceEnabled ? 'Update Maintenance Settings' : 'Save & Enable Maintenance' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
