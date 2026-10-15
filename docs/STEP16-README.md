# Step 16: Enquiries management

| Path | Purpose |
|---|---|
| routes/web.php | Adds enquiries.index / create / show (replaces the file) |
| app/Http/Controllers/Admin/EnquiryController.php | Three page actions |
| app/Services/PriceResolver.php | Price per unit for a quantity (base price or the biggest reached tier) |
| app/Services/Admin/EnquiryService.php | Status change, notes, assign, manual enquiry with price snapshot |
| app/Livewire/Admin/Enquiries/EnquiryTable.php | List with search and status / channel filters |
| app/Livewire/Admin/Enquiries/EnquiryShow.php | Details, status, handler, notes |
| app/Livewire/Admin/Enquiries/EnquiryForm.php | Add an enquiry by hand (existing or new customer) |
| resources/views/... | Pages and Livewire views |
| resources/views/admin/partials/rail.blade.php | Enquiries menu item is now active (replaces the file) |
| resources/views/admin/dashboard.blade.php | Reference links to the enquiry (replaces the file) |
| public/assets/admin/css/admin.css | Appended styles (replaces the file, includes steps 15a and 15b) |
| tests/ | 35 new tests |

## Rules

- The first staff member who moves a New enquiry to another status becomes its handler. An existing handler is never replaced by a status change. "Assign to me" changes it on purpose.
- Prices on an enquiry are a snapshot saved at creation. They never change when the catalogue changes.
- Enquiries cannot be deleted (they are a record). Use the Closed status instead. Role based permissions come with Step 17.
- A manual enquiry needs a customer (existing, or new with a phone, WhatsApp or email) and at least one product. Each product can appear once.
- Customer search in the form shows up to 8 matches. A full Customers screen comes in Step 17.

## Setup

Copy the files, then `php artisan optimize:clear` and `php artisan test`.
