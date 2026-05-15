@extends('layouts.app')
@section('title', 'About')

@section('content')
<div class="page-heading">
    <h1>About To-Do App</h1>
    <p>A powerful task management system built with Laravel.</p>
</div>

<div class="card">
    <div class="card-body" style="max-width:600px">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem">
            <div style="width:60px;height:60px;background:#e7ecff;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:26px;color:#3B5BDB">
                <i class="fa-solid fa-check-double"></i>
            </div>
            <div>
                <h2 style="font-size:18px;font-weight:700;color:#212529">To-Do App</h2>
                <p style="font-size:13px;color:#868e96">Version 1.0 · Built with Laravel 11</p>
            </div>
        </div>

        <p style="font-size:14px;color:#495057;line-height:1.7;margin-bottom:1rem">
            A full-featured task management application with secure authentication,
            per-user data isolation, priority management, and an interactive dashboard
            with graphical reports.
        </p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-top:1.25rem">
            @foreach([
                ['fa-lock','Secure Auth','Laravel Breeze-style authentication'],
                ['fa-user-shield','Per-user Data','Tasks scoped to each user'],
                ['fa-chart-pie','Dashboard Charts','Visual productivity reports'],
                ['fa-filter','Filters','Filter by status & priority'],
                ['fa-pen','CRUD Tasks','Create, edit, delete, complete'],
                ['fa-mobile-screen','Responsive','Works on all screen sizes'],
            ] as [$icon, $title, $desc])
            <div style="display:flex;gap:10px;align-items:flex-start;padding:.75rem;background:#f8f9fa;border-radius:10px">
                <div style="width:34px;height:34px;background:#e7ecff;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#3B5BDB;flex-shrink:0">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:13px;color:#212529">{{ $title }}</div>
                    <div style="font-size:12px;color:#868e96">{{ $desc }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection