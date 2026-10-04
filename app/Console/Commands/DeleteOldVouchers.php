<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteOldVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vouchers:clean-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina los comprobantes de pago (váuchers) que tengan más de 6 meses de antigüedad para liberar espacio en el servidor.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener la fecha de hace 6 meses
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        // Buscar todos los pagos anteriores a esa fecha que aún tengan váucher
        $payments = Payment::whereNotNull('voucher_path')
            ->where('created_at', '<', $sixMonthsAgo)
            ->get();

        $count = 0;

        foreach ($payments as $payment) {
            if (Storage::disk('public')->exists($payment->voucher_path)) {
                Storage::disk('public')->delete($payment->voucher_path);

                // Limpiar el campo en la BD (opcional, pero recomendado)
                $payment->update(['voucher_path' => null]);

                $count++;
            }
        }

        $this->info("Se han eliminado {$count} váuchers antiguos del servidor.");
    }
}
