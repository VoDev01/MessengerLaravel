<?php

namespace App\Services\Interface;

interface APIService
{
    public function index();

    public function show(int $id);

    public function edit(int $id, array $validated);

    public function store(array $validated);

    public function delete(int $id);
}