<?php

namespace App\Http\Controllers;

use App\Http\Requests\FakultasRequest;
use App\Http\Resources\FakultasResource;
use App\Models\Fakultas;
use App\Services\FakultasService;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function __construct(
        protected FakultasService $fakultasService
    ) {}

    public function index(Request $request)
    {
        return FakultasResource::collection(
            $this->fakultasService->index(
                $request->only([
                    'search',
                    'per_page',
                    'is_aktif',
                    'fakultas_siakad_id',
                ])
            )
        );
    }

    public function show(Fakultas $fakultas)
    {
        return new FakultasResource(
            $this->fakultasService->show($fakultas)
        );
    }

    public function store(FakultasRequest $request)
    {
        return new FakultasResource(
            $this->fakultasService->store(
                $request->validated()
            )
        );
    }

    public function update(
        FakultasRequest $request,
        int $id
    ) {

        return new FakultasResource(
            $this->fakultasService->update(
                $id,
                $request->validated()
            )
        );
    }

    public function destroy(Fakultas $fakultas)
    {
        $this->fakultasService->destroy($fakultas);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }

    public function previewSync()
    {
        return response()->json(
            $this->fakultasService->previewSyncFromSiakad()
        );
    }

    public function sync()
    {
        return response()->json(
            $this->fakultasService->syncFromSiakad()
        );
    }
}
