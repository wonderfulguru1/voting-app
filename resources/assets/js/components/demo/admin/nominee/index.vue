<template>
<div class="row">
	<div class="col-md-3" style="max-width: 250px;">
		<ul class="list-group">
			<router-link 
				tag="li" 
				class="list-group-item" 
				:to="{name: 'Manage Nominee'}" 
				exact 
				replace
				:class="{'active':position_id==0}">
				All Position
			</router-link>
			<router-link 
				v-for="position in data.positions"
				:key="position.id"
				tag="li" 
				class="list-group-item" 
				:to="{query: {position_id:position.id}}"
				exact
				replace>
				{{ position.name }}
			</router-link>
		</ul>
	</div>
	<div class="col-md-9 panel panel-default">
		<div class="panel-body table-responsive">
			<div class="form-group">
				<router-link :to="{name: 'Add Nominee', query:{position_id:position_id}}" class="btn btn-success"><i class="fa fa-plus"></i> Add Nominee</router-link>
				<button class="btn btn-warning" @click="util.showModal('#import_data')">
						<i class="fa fa-refresh"></i> Import Nominees
					</button>
					<button class="btn btn-danger" @click="loadNominee()">
						<i class="fa fa-refresh"></i> Publish Nominees
					</button>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th></th>
						<th>Name</th>
						<th>Member ID</th>
						<th>Course</th>
						<th>Position</th>
						<th>Partylist</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="nominee in nominees" :key="nominee.id">
						<td>
							<img :alt="nominee.name" :src="data.storageURL+nominee.image" class="thumbnail" style="height: 60px;width: 60px;">
						</td>
						<td>{{ nominee.name}}</td>
						<td>{{ nominee.student_id}}</td>
						<td>{{ nominee.course}}</td>
						<td>{{ nominee.position}}</td>
						<td>{{ nominee.partylist}}</td>
						<td>
							<router-link :to="{name: 'Edit Nominee', params:{id:nominee.id}}" class="btn btn-info">
								<i class="fa fa-edit"></i> Edit
							</router-link>
							<button 
								class="btn btn-danger" 
								@click="util.showModal('#delete-nominee-modal');id=nominee.id">
								<i class="fa fa-trash"></i> Delete
							</button>
						</td>
					</tr>
					<tr v-if="nominees.length < 1">
						<td colspan="7">No nominees</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<modal id="delete-nominee-modal">
		<modal-header>Delete Nominee</modal-header>
		<modal-body>
			<h3>Are you sure to delete this nominee?</h3>
		</modal-body>
		<modal-footer>
			<button class="btn btn-danger" @click="deleteNominee()">Delete</button>
			<button class="btn btn-default" @click="util.hideModal('#delete-nominee-modal')">
				Back
			</button>
		</modal-footer>
	</modal>

	
	<modal id="import_data">
			<form @submit.prevent="start" id="start_form">

				<div class="file-upload">
					<h2>Upload File</h2>
					<input type="file" @change="handleFileUpload" />
					<button @click="uploadFile">Import Voters</button>
					<div v-if="uploadProgress">Progress: {{ uploadProgress }}%</div>
					<div v-if="uploadStatus">{{ uploadStatus }}</div>
				</div>
			</form>
		</modal>
</div>
</template>

<script>
import modal from '../../../mycomponents/modal/modal.vue';

export default{
	components: { modal },

	data: function () {
		return {
			id: 0,
			selectedFile: null,
			uploadProgress: null,
			uploadStatus: ''
		}
	},

	created: function () {
		this.refreshNominee();
	},

	methods: {
		handleFileUpload(event) {
			this.selectedFile = event.target.files[0];
		},
		async uploadFile() {
			if (!this.selectedFile) {
				alert('Please select a file first.');
				return;
			}
			this.util.notify('Importing  Voters', 'progress', 0);

			const formData = new FormData();
			formData.append('file', this.selectedFile);
			var vm = this;
			axios.post(config.API +'nominee/import-voters', formData, {
					headers: {
						'Content-Type': 'multipart/form-data',
						'Authorization': localStorage['Access Token']
					},
					onUploadProgress: function (progressEvent) {
						vm.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
						vm.util.notify('Importing  Voters', 'progress', vm.uploadProgress);
						vm.util.hideModal('#import_data');
						$.notifyClose();
					}
				}).then(function (response) {
					vm.util.notify('Nominees uploaded successfully', 'sucess');
					$.notifyClose();
				})
				.catch(function (error) {
					vm.util.notify('File upload failed', 'error');
					$.notifyClose();
				});
		},
		search: function () {

		},

		loadNominee: function () {
			var vm = this;
			this.util.notify('Loading Nominee', 'loading');
			axios.post(config.API + 'nominee/load-voters')
				.then(response => {
					console.log(response)
					$.notifyClose();
					vm.$router.go()
					//vm.data.voters = response.data;
				})
				.catch(error => {
					$.notifyClose();
					vm.util.showResult(error);
				})
		},


		deleteNominee: function () {
			this.util.hideModal('#delete-nominee-modal');
			var vm = this;
			this.util.notify('Deleting nominee', 'loading');
			axios.delete(config.API+'nominee/'+this.id)
				 .then(response=>{
				 	$.notifyClose();
				 	if(vm.util.showResult(response, 'success'))
				 	vm.refreshNominee();
				 })
				 .catch(error=>{
				 	$.notifyClose();
				 	vm.util.showResult(error, 'error');
				 })
		},
		
		refreshNominee: function () {
			var vm = this;
			this.util.notify('Refreshing Nominees', 'loading');
			axios.get(config.API+'nominee')
				.then(response=>{
					$.notifyClose();
					console.log(response);
					vm.data.nominees = response.data;
				})
				.catch(error=>{
					$.notifyClose();
					vm.showResult(error);
				})
		},

		getPosition: function (id) {
			var positions = this.data.positions;
			for (var i in positions) 
				if (positions[i].id == id)
					return positions[i]['name'];
		},

		getPartylist: function (id) {
			var partylists = this.data.partylists;
			for (var i in partylists)
				if (partylists[i].id == id)
					return partylists[i]['name'];
			return 'No partylists';
		}
	},

	computed: {
		nominees: function () {
			var nominees = [];
			var y = this.data.nominees;
			for (var nominee in this.data.nominees) {
				if (y[nominee].position_id == this.position_id || this.position_id == 0) {
					var x = y[nominee];
					x.position = this.getPosition(y[nominee].position_id);
					x.partylist = this.getPartylist(y[nominee].partylist_id);
					nominees.push(x);
				}
			}
			return nominees;
		},

		position_id: function () {
			return this.$route.query.position_id ? this.$route.query.position_id : 0;
		}
	}
}
</script>