<template>
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-3" v-for="employee in employees">
        <div class="card">
          
            <div class="card-header">{{ employee.first_name }} {{ employee.last_name }}</div>
          <div class="card-body">
            <p>{{ employee.email }}</p>
            <p>{{ employee.phone }}</p>
            <div>
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
    <div id="addEmployeeModal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add New Employee</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="">
              <div class="form-group">
                <label for="newEmployeeFirstName">First Name</label>
                <input type="text" name="newEmployeeFirstName" id="newEmployeeFirstName" class="form-control" v-model="employeeFirstName">
              </div>
              <div class="form-group">
                <label for="newEmployeeLastName">Last Name</label>
                <input type="text" name="newEmployeeLastName" id="newEmployeeLastName" class="form-control" v-model="employeeLastName">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        employees: [],
        pageNumber: 1,
        totalPages: 1
      };
    },
    props: ['userData', 'companyId'],
    computed: {
      image: function() {
        return "https://via.placeholder.com/150";
      }
    },
    methods: {
      /* employeePage(company_id) {
        return "/home/company/" + company_id;
      }, */
      read() {
        // let url = "/companies?page=" + this.pageNumber;
        window.axios.get("/employees", {params: {
          page: this.pageNumber,
          company_id: this.companyId
        }}).then(({data}) => {
          console.log(data);
          this.employees = data.data;
          this.totalPages = data.last_page;
          this.pageNumber = data.current_page;
        });
      },
      update(id, foobar) {
        console.log(id, foobar);
        /* window.axios.put(`/employees/${id}`, {foobar}).then(() => {
          
        }); */
      }
    },
    mounted() {
      console.log(this.userData, this.companyId);
    },
    created() {
      this.read();
    }
  }
</script>