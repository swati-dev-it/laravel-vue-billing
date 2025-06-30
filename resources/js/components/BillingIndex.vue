<template>
  <div class="container mt-5">
    <h2 class="mb-4">Invoice List</h2>

    <!-- Filter Section -->
    <div class="card p-3 mb-4">
      <div class="row g-3">
        <div class="col-md-3">
          <input
            v-model="filters.search"
            type="text"
            class="form-control"
            placeholder="Search any field"
          />
        </div>
        <div class="col-md-3">
          <input
            v-model="filters.start_date"
            type="date"
            class="form-control"
            placeholder="Start Date"
          />
        </div>
        <div class="col-md-3">
          <input
            v-model="filters.end_date"
            type="date"
            class="form-control"
            placeholder="End Date"
          />
        </div>
        <div class="col-md-3 d-flex align-items-center gap-2">
          <button class="btn btn-primary" @click="applyFilter">Apply</button>
          <button class="btn btn-secondary" @click="resetFilter">Reset</button>
        </div>
      </div>
    </div>

    <!-- Invoice Table -->
    <div class="card">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th v-for="field in fields" :key="field.key" @click="sort(field.key)" style="cursor: pointer">
                {{ field.label }}
                <span v-if="sortKey === field.key">
                  {{ sortDir === 'asc' ? '▲' : '▼' }}
                </span>
                <span v-else class="text-gray-400">↕</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="invoice in invoices.data" :key="invoice.id" @click="viewInvoice(invoice.id)" style="cursor: pointer;">
              <td>{{ invoice.invoice_number }}</td>
              <td>{{ invoice.invoice_date }}</td>
              <td>{{ invoice.due_date }}</td>
              <td>₹{{ invoice.total_amount }}</td>
              <td>{{ invoice.gst_number }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="card-footer d-flex justify-content-end align-items-center">
        <div>
            <template v-for="(link, index) in invoices.links" :key="index">
            <button
                v-if="link.url"
                @click="goToPage(link.url)"
                class="btn btn-sm me-1"
                :class="link.active ? 'btn-primary' : 'btn-outline-primary'"
                v-html="link.label"
            ></button>
            </template>
        </div>
      </div>

    <!-- Invoice Detail Modal -->
        <div class="modal fade" id="invoiceModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow position-relative">

                <!-- Green Top Border -->
                <div class="bg-success" style="height: 5px;"></div>

                <!-- Watermark Logo -->
                <img
                    src="/images/taptik_logo.png"
                    alt="Watermark Logo"
                    class="position-absolute top-50 start-50 translate-middle"
                    style="opacity: 0.06; height: 80px; pointer-events: none; z-index: 0;"
                />

                <!-- Modal Body -->
                <div class="modal-body px-5 py-4 position-relative" style="z-index: 1; background-color: transparent;">

                    <!-- Logo & Company Info -->
                    <div class="text-center mb-4">
                    <img
                        :src="`/images/taptik_logo.png`"
                        alt="Company Logo"
                        class="mb-2"
                        style="height: 60px;"
                    />
                    <h5 class="fw-bold mb-1">{{ selectedCompany.name }}</h5>
                    <p class="text-muted small mb-0">
                        {{ selectedCompany.address }}<br />
                        {{ selectedCompany.contact }}
                    </p>
                    </div>

                    <!-- Invoice Title -->
                    <h2 class="text-center fw-bold text-uppercase my-4" style="color: #a64b00;">INVOICE</h2>

                    <!-- Bill To -->
                    <div class="mb-4">
                    <h6 class="fw-bold text-success">Bill To</h6>
                    <p class="mb-0 fw-bold">{{ selectedInvoice?.user?.name || 'N/A' }}</p>
                    <p class="text-muted small">
                        {{ selectedInvoice?.user?.address }}<br>
                        {{ selectedInvoice?.user?.phone }}
                    </p>
                    </div>

                    <!-- Invoice Info -->
                    <div class="row mb-4">
                    <div class="col-4">
                        <strong class="text-muted small">Invoice#</strong>
                        <p>{{ selectedInvoice?.invoice_number }}</p>
                    </div>
                    <div class="col-4">
                        <strong class="text-muted small">Invoice Date</strong>
                        <p>{{ selectedInvoice?.invoice_date }}</p>
                    </div>
                    <div class="col-4">
                        <strong class="text-muted small">Due Date</strong>
                        <p>{{ selectedInvoice?.due_date }}</p>
                    </div>
                    </div>

                    <!-- Items Table -->
                    <table class="table table-borderless mb-4" style="background-color: transparent;">
                        <thead class="border-bottom text-muted small">
                            <tr>
                            <th>#</th>
                            <th>Items</th>
                            <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody style="background-color: transparent;">
                            <tr v-for="(item, index) in selectedInvoiceItems" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.item }}</td>
                            <td class="text-end">₹{{ item.amount }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Notes & Totals -->
                    <div class="row">
                    <div class="col-6">
                        <p class="text-muted mb-1"><strong>Notes</strong></p>
                        <p class="small text-muted">Thanks for your business.</p>
                    </div>
                    <div class="col-6 text-end">
                        <p>Sub Total: ₹{{ selectedInvoice?.sub_total }}</p>
                        <p>Tax : {{ selectedInvoice?.tax_rate }}%</p>
                        <h5 class="fw-bold mt-3">Total: ₹{{ selectedInvoice?.total_amount }}</h5>
                        <div class="border-top pt-2 mt-2"></div>
                    </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 d-flex justify-content-end px-5 pb-4">
                    <button class="btn btn-danger" @click="downloadInvoicePDF(selectedInvoice.id)">
                    Download PDF
                    </button>
                    <button class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                </div>
                
                </div>
            </div>
            </div>



    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const invoices = ref({
  data: [],
  links: [],
  current_page: 1,
  last_page: 1,
});

const filters = ref({
  search: '',
  start_date: '',
  end_date: '',
});

const selectedInvoice = ref(null);
const selectedInvoiceItems = ref([]);
const selectedCompany = ref({});

const sortKey = ref('invoice_date');
const sortDir = ref('desc');

const fields = [
  { key: 'invoice_number', label: 'Invoice #' },
  { key: 'invoice_date', label: 'Invoice Date' },
  { key: 'due_date', label: 'Due Date' },
  { key: 'total_amount', label: 'Total (₹)' },
  { key: 'gst_number', label: 'GST No' },
];

const fetchInvoices = async (page = 1) => {
  const query = new URLSearchParams({
    page,
    sort: sortKey.value,
    direction: sortDir.value,
    search: filters.value.search || '',
    start_date: filters.value.start_date || '',
    end_date: filters.value.end_date || '',
  });
  const response = await fetch(`/billing/data?${query.toString()}`);
  invoices.value = await response.json();
};

const sort = (key) => {
  if (sortKey.value === key) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = key;
    sortDir.value = 'asc';
  }
  fetchInvoices(1);
};

const applyFilter = () => {
  fetchInvoices(1);
};

const resetFilter = () => {
  filters.value = {
    search: '',
    start_date: '',
    end_date: '',
  };
  fetchInvoices(1);
};

const goToPage = (url) => {
  const page = new URL(url).searchParams.get('page');
  fetchInvoices(page);
};

const viewInvoice = async (id) => {
  const res = await fetch(`/billing/${id}/details`);
  const data = await res.json();

  selectedInvoice.value = data.invoice;
  selectedInvoiceItems.value = data.items;
  selectedCompany.value = data.company;

  const modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
  modal.show();
};

const downloadInvoicePDF = async (id) => {
  window.open(`/billing/${id}/download`, '_blank');
};

onMounted(() => {
  fetchInvoices();
});
</script>
