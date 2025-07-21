<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class PasienController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pasien",
     *     summary="Ambil semua data pasien",
     *     tags={"Pasien"},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil ambil data pasien"
     *     )
     * )
     */
    public function index()
    {
        return Pasien::all();
    }
}
