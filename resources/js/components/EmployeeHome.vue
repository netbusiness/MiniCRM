<template>
  <div class="container">
    <div class="row mb-3"><button class="btn btn-success" v-on:click="newEmployee()">Add New Employee</button></div>
    <div class="row">
      <div class="col-md-4 mb-3" v-for="employee in employees">
        <div class="card">
          <div class="card-header">{{ employee.first_name }} {{ employee.last_name }}</div>
          <div class="card-body">
            <p>{{ employee.phone }}</p>
            <p>{{ employee.email }}</p>
            <div>
              <a href="#" class="btn btn-primary" v-on:click="editEmployee(employee)">Edit</a>
              <a href="#" class="btn btn-danger float-right" v-on:click="confirmDelete(employee)">Delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <paginator v-bind:totalPages="totalPages" v-bind:pageNumber="pageNumber" v-on:changePage="gotoPage($event)"></paginator>
    <div id="addEmployeeModal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="isNewEmployee" class="modal-title">Add New Employee</h5>
            <h5 v-else class="modal-title">Edit Employee</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="">
              <div class="form-group">
                <label for="newEmployeeFirstName">First Name*</label>
                <input type="text" name="newEmployeeFirstName" id="newEmployeeFirstName" class="form-control" v-model="employeeFirstName">
              </div>
              <div class="form-group">
                <label for="newEmployeeLastName">Last Name*</label>
                <input type="text" name="newEmployeeLastName" id="newEmployeeLastName" class="form-control" v-model="employeeLastName">
              </div>
              <div class="form-group">
                <label for="newEmployeePhone">Phone</label>
                <input type="text" name="newEmployeePhone" id="newEmployeePhone" class="form-control" v-model="employeePhone">
              </div>
              <div class="form-group">
                <label for="newEmployeeEmail">Email</label>
                <input type="text" name="newEmployeeEmail" id="newEmployeeEmail" class="form-control" v-model="employeeEmail">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" v-on:click="saveEmployee()">Save changes</button>
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
        totalPages: 1,
        employeeId: null,
        employeeFirstName: '',
        employeeLastName: '',
        employeePhone: '',
        employeeEmail: '',
        isNewEmployee: false
      };
    },
    props: ['userData', 'companyId'],
    computed: {
      image: function() {
        return "https://via.placeholder.com/150";
      }
    },
    methods: {
      newEmployee() {
        this.employeeId = null;
        this.employeeFirstName = '';
        this.employeeLastName = '';
        this.employeePhone = '';
        this.employeeEmail = '';
        this.isNewEmployee = true;
        $("#addEmployeeModal").modal("show");
      },
      editEmployee: function(employee) {
        this.employeeId = employee.id;
        this.employeeFirstName = employee.first_name;
        this.employeeLastName = employee.last_name;
        this.employeePhone = employee.phone;
        this.employeeEmail = employee.email;
        this.isNewEmployee = false;
        $("#addEmployeeModal").modal("show");
      },
      saveEmployee: function(bogus /* Need a param here or it's "nOt a fUnCtIoN" */) {
        var employee = {};
        employee.id = this.employeeId;
        employee.company_id = this.companyId;
        employee.first_name = this.employeeFirstName;
        employee.last_name = this.employeeLastName;
        employee.phone = this.employeePhone;
        employee.email = this.employeeEmail;
        
        if (this.isNewEmployee) {
          this.create(employee);
        } else {
          this.update(employee.id, employee);
        }
        
        $("#addEmployeeModal").modal("hide");
      },
      confirmDelete: function(employee) {
        bootbox.confirm("Are you sure you want to delete " + employee.first_name + " " + employee.last_name + "?", (yesNo) => {
          if (yesNo) {
            this.delete(employee.id);
          }
        });
      },
      gotoPage: function(pageNumber) {
        console.log(pageNumber);
        this.pageNumber = pageNumber;
        this.read();
      },
      read() {
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
      update(id, employee) {
        window.axios.put(`/employees/${id}`, employee).then(() => {
          this.read();
        }/*, (error) => {
          console.log(error.response);
        } */);
      },
      create(employee) {
        window.axios.post("/employees", employee).then(() => {
          this.read();
        });
      },
      delete(id) {
        window.axios.delete(`/employees/${id}`).then(() => {
          this.read();
        });
      }
    },
    created() {
      this.read();
    }
  }
</script>