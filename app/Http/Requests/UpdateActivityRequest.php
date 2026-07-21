<?php

namespace App\Http\Requests;

/**
 * Validasi untuk Update Kegiatan.
 * Mewarisi seluruh aturan dari StoreActivityRequest — tidak ada perbedaan aturan.
 * Dipisah agar Controller tetap ekspresif (menunjukkan intent store vs update).
 */
class UpdateActivityRequest extends StoreActivityRequest
{
    //
}
