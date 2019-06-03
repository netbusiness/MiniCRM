<template>
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-3" v-for="company in companies">
        <div class="card">
          <a v-bind:href="companyPage(company.id)">
            <img class="card-img-top" v-bind:src="image">
            <div class="card-header">{{ company.name }}</div>
          </a>
          <div class="card-body">
            <p>{{ company.email }}</p>
            <p>{{ company.website }}</p>
            <div v-if="userData.access_level == 10">
              <a href="#" class="btn btn-primary">Edit</a>
              <a href="#" class="btn btn-danger float-right">Delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="mt-3" v-if="totalPages > 1">
      <ul class="pagination justify-content-center">
        <li class="page-item" v-bind:class="{disabled: pageNumber == 1}"><a class="page-link" href="#">&lt;</a></li>
        <li class="page-item" v-bind:class="{active: pageNumber == i}" v-for="i in totalPages"><a class="page-link" href="#">{{ i }}</a></li>
        <li class="page-item" v-bind:class="{disabled: pageNumber == totalPages}"><a class="page-link" href="#">&gt;</a></li>
      </ul>
    </nav>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        companies: [],
        pageNumber: 1,
        totalPages: 1
      };
    },
    props: ['userData'],
    computed: {
      image: function() {
        return "https://via.placeholder.com/150";
      }
    },
    methods: {
      companyPage(company_id) {
        return "/home/company/" + company_id;
      },
      read() {
        // let url = "/companies?page=" + this.pageNumber;
        window.axios.get("/companies", {params: {page: this.pageNumber}}).then(({data}) => {
          console.log(data);
          this.companies = data.data;
          this.totalPages = data.last_page;
          this.pageNumber = data.current_page;
        });
      },
      update(id, foobar) {
        console.log(id, foobar);
        /* window.axios.put(`/companies/${id}`, {foobar}).then(() => {
          
        }); */
      }
    },
    mounted() {
      // console.log(this.userData);
    },
    created() {
      this.read();
    }
  }
</script>