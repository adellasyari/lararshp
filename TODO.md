# TODO: Create Index View for Receptionists to Display Doctor Appointments

## Steps to Complete
1. Update the temu_dokter migration to include necessary fields (idpet, idpemilik, tanggal, waktu, status).
2. Create the TemuDokter model with relationships to Pet and Pemilik.
3. Create TemuDokterController in Resepsionis namespace with index method.
4. Create the index view for temu dokter in resepsionis.
5. Add routes for temu dokter in web.php under resepsionis middleware.
6. Update the resepsionis dashboard to include a link to temu dokter.
7. Run the migration to apply database changes.
8. Test the functionality.
