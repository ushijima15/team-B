<template>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card" key="admin">
				<div class="card-body">
					<div class="d-flex justify-content-start mb-3">
						<div class="mr-auto">
							<span class="span-header">{{title}}</span>
						</div>
						<div class="align-self-center">
							<button type="button" class="btn btn-dark" @click="onBack">戻る</button>
						</div>
					</div>

					<div class="form-group">
						<label>名前</label>
						<input v-model="content.user_name" type="text" class="form-control" readonly="readonly">
					</div>

					<div class="form-group">
						<label>タグ</label>
						<select v-model="content.tag" class="form-control">
							<option>フィロソフィー勉強会</option>
							<option>NG勉強会</option>
						</select>
					</div>

					<div class="form-group">
						<div class="form-group">
							<textarea v-model="content.content_text" class="form-control" style="height: 300px;" placeholder="本文内容"></textarea>
						</div>
					</div>

					<form>
						<!-- </template> -->
						<div class="row-line">
							<transition name="fade" mode="out-in">
								<div class="alert alert-danger" role="alert" v-if="invalid">
									{{errorMessage}}
								</div>
							</transition>
						</div>
					</form>
							
					<div class="d-flex justify-content-start mt-4">
						<div class="mr-auto">
							<button type="button" class="btn btn-outline-danger" v-if="enable_delete" @click="onDelete">投稿を削除</button>
						</div>
						<div class="mr-3">
							<button type="button" class="btn btn-dark" @click="onBack">キャンセル</button>
						</div>
						<div v-if="mode!='create'">
							<button type="button" class="btn btn-primary" @click="onStore">保存する</button>
						</div>
						<div v-else>
							<button type="button" class="btn btn-primary" @click="onStore">登録する</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<loading :active.sync="isLoading" :is-full-page="fullPage"></loading>
</div>
</template>

<script>
import moment from 'moment';
export default {
	props: ['contentId'],
	data () {
		return {
			content: {
				id: '',
				goriller_id: '',
				tag: '',
				user_name: '',
				content_text: '',
			},
			
			invalid: false,
			errorMessage: '',

			isLoading: false,
			fullPage: false,
		}
	},
	computed: {
		own: function () {
			return this.$store.state.user
		},
		title: function () {
			return this.mode == 'create' ? '投稿内容 新規作成' : '投稿内容 編集'
		},
		mode: function () {
			return this.contentId ? 'update' : 'create'
		},
		enable_delete: function () {
			if (this.mode == 'create') {
					return false
			}
			return this.own.content_id != this.contentId
		},
	},
	watch: {
		// 
	},
	created () {
		this.getItems()
	},
	methods: {
		getItems: function () {
			this.isLoading = true;
			const api = axios.create()
			if (this.mode == "create") {
				this.isLoading = false
			} else {
				axios.all([
						api.get('/api/content/'+this.contentId),
				]).then(axios.spread((res1, res2, res3) => {
						this.content = res1.data
						this.isLoading = false
				}))
			}
		},
		onStore: function () {
			this.invalid = false
			this.errorMessage = ''

			let _this = this
			if (this.mode == 'create') {
				axios.post('/api/content', {
					content: this.content,
				})
				.then(function (resp) {
					if (resp.data.result) {
						alert('登録しました。')
						_this.$router.go(-1)
					} else {
						_this.errorMessage = resp.data.errorMessage
						_this.invalid = true
					}
				})
				.catch(function (resp) {
					console.log(resp)
				});
			} else {
				axios.put('/api/content/'+this.content.id, {
					content: this.content,
				})
				.then(function (resp) {
					if (resp.data.result) {
							alert('更新しました。')
							_this.$router.go(-1)
					} else {
							_this.errorMessage = resp.data.errorMessage
							_this.invalid = true
					}
				})
				.catch(function (resp) {
						console.log(resp)
				});
			}
		},
		onBack: function () {
			this.$router.go(-1)
		},
		onDelete: function () {
			if (!confirm('削除してもよろしいですか？')) {
				return
			}
			let _this = this
			axios.delete('/api/content/'+this.content.id)
			.then(function (resp) {
				alert('削除しました。')
				_this.$router.go(-1)
			})
			.catch(function (resp) {
				console.log(resp)
			})
			.finally(function () {
				//
			})
		},
	},
	components: {
			// Loading
	},
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
.row-line {
	padding-left: 1.5rem;
	padding-right: 1.5rem;
	padding-bottom: 1rem;
}
.form-content {
	width: 12rem;
	padding-left: 1rem;
	padding-right: 1rem;
	display: inline-block;
}
.form-content-lg {
	width: 15rem;
	padding-left: 1rem;
	padding-right: 1rem;
	display: inline-block;
}
.required-label label:after {
	display: inline-block;
	margin-left: 5px;
	padding: 2px 4px;
	border-radius: 3px;
	background-color: #ec5d53;
	color: #fff;
	content: "必須";
	font-size: 9px;
	line-height: 10px;
}
</style>
