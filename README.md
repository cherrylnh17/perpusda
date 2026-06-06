crontab -e
# tambahkan baris ini:
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1


php artisan tinker --execute="\App\Models\NotifikasiKenaikan::generateRange(); echo 'Done';"
