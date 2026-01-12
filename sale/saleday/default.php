<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Daily Summary</title>
  <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <style>
    body { font-family: sans-serif; margin: 20px; }
    .form-section { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
    label { display: block; margin-top: 8px; font-weight: bold; }
    input { padding: 5px; width: 200px; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ddd; padding: 5px; text-align: center; }
    button { margin-top: 15px; padding: 10px 15px; cursor: pointer; }
  </style>
</head>
<body>
<div id="app">
  <h2>📊 บันทึกสรุปยอดขายประจำวัน</h2>

  <!-- Daily Summary -->
  <div class="form-section">
    <label>สาขา:</label>
    <input type="number" v-model="form.branch_id">

    <label>วันที่:</label>
    <input type="date" v-model="form.summary_date">

    <label>ยอดขายรวม:</label>
    <input type="number" v-model="form.total_sales">

    <label>ส่วนลด / คูปอง:</label>
    <input type="number" v-model="form.total_discount">

    <label>เงินสด (ระบบ):</label>
    <input type="number" v-model="form.cash_recorded">

    <label>เงินสด (นับจริง):</label>
    <input type="number" v-model="form.cash_counted">

    <label>เงินโอน:</label>
    <input type="number" v-model="form.transfer_amount">

    <label>ช่องทางอื่น (เช่น QR):</label>
    <input type="number" v-model="form.other_payment">
  </div>

  <!-- Stock Balance -->
  <div class="form-section">
    <h3>📦 สินค้าคงเหลือ</h3>
    <table>
  <thead>
    <tr>
      <th>สินค้า</th>
      <th>ราคา</th>
      <th>ยกมา</th>
      <th>รับเข้า</th>
      <th>ขายได้</th>
      <th>คงเหลือ</th>
      <th>ยอดขาย(฿)</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="(s, index) in form.stocks" :key="index">
      <td>{{ s.name }}</td>
      <td>{{ s.price }}</td>
      <td>{{ s.opening_qty }}</td>
      <td><input type="number" v-model.number="s.received_qty"></td>
      <td>{{ calcSold(s) }}</td>
      <td><input type="number" v-model.number="s.closing_qty"></td>
      <td>{{ calcSold(s) * s.price }}</td>
    </tr>
  </tbody>
</table>

<p><b>รวมยอดขาย: {{ totalSales }}</b></p>

    <button @click="addStock">+ เพิ่มสินค้า</button>
  </div>

  <button @click="saveSummary">💾 บันทึก</button>

  <div v-if="message" style="margin-top: 15px; font-weight: bold;">
    {{ message }}
  </div>
</div>

<script>
const { createApp } = Vue;

createApp({
  data() {
    return {
      form: {
        branch_id: 1,
        summary_date: new Date().toISOString().slice(0, 10),
        total_sales: 0,
        total_discount: 0,
        cash_recorded: 0,
        cash_counted: 0,
        transfer_amount: 0,
        other_payment: 0,
        stocks: []
      },
      message: ""
    };
  },
  computed: {
    totalSales() {
      return this.form.stocks.reduce((sum, s) => {
        return sum + (this.calcSold(s) * s.price);
      }, 0);
    }
  },
  methods: {
    async loadProducts() {
      let res = await axios.get("../../api/get_branch_products.php?branch_id=" + this.form.branch_id);
      if (res.data.status === "success") {
        this.form.stocks = res.data.data.map(p => ({
          product_id: p.product_id,
          name: p.name,
          price: Number(p.price),
          opening_qty: Number(p.opening_qty),
          received_qty: 0,
          closing_qty: Number(p.opening_qty)
        }));
      }
    },
    calcSold(s) {
      return (s.opening_qty + (s.received_qty || 0)) - (s.closing_qty || 0);
    },
    async saveSummary() {
      // set total_sales จาก computed
      this.form.total_sales = this.totalSales;

      try {
        let res = await axios.post("../../api/save_daily_summary.php", this.form);
        this.message = res.data.message;
      } catch (err) {
        this.message = "❌ Error: " + err.message;
      }
    }
  },
  mounted() {
    this.loadProducts(); // โหลดสินค้าตอนเปิดหน้า
  }
}).mount("#app");

</script>
</body>
</html>
