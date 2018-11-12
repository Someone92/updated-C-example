import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Response, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';

import { AppConfig } from '../config/app.config';

@Injectable()
export class MenuService {
	_url: string;

	constructor(private config: AppConfig,
				private httpClient: HttpClient) { }

	getMenus(): Observable<any[]> {
		this._url = this.config.getConfig('api') + '/api/menu';

		return this.httpClient.get(this._url)
			.map(this.handleData)
			.catch(this.handleError);
	}

	getMenu(item): Observable<any[]> {
		this._url = this.config.getConfig('api') + '/api/admin/menu/' + item;

		return this.httpClient.get(this._url)
			.map(this.handleData)
			.catch(this.handleError)
	}

	createMenu(item): Observable<any[]> {
		this._url = this.config.getConfig('api') + '/api/admin/menu';

		return this.httpClient.post(this._url, item)
			.map(this.handleData)
			.catch(this.handleError)
	}

	updateMenu(id, item): Observable<any[]> {
		this._url = this.config.getConfig('api') + '/api/admin/menu/' + id;

		return this.httpClient.patch(this._url, item)
			.map(this.handleData)
			.catch(this.handleError)
	}

	delMenu(id): Observable<any[]> {
		this._url = this.config.getConfig('api') + '/api/admin/menu/' + id;

		return this.httpClient.delete(this._url)
			.map(this.handleData)
			.catch(this.handleError);
	}

	getMenuBuilder(id): Observable<any[]> {
		this._url = this.config.getConfig('api') + '/api/admin/menu/' + id + '/builder';
		
		return this.httpClient.get(this._url)
			.map(this.handleData)
			.catch(this.handleError);
	}


	updateMenuItem(parentId, id, item): Observable<any[]> {
		this._url = this.config.getConfig('api') + '/api/admin/menu/' + parentId + '/builder/' + id;

		return this.httpClient.patch(this._url, item)
			.map(this.handleData)
			.catch(this.handleError);
	}

	delMenuItem(parentId, id): Observable<any[]> {
		this._url = this.config.getConfig('api') + '/api/admin/menu/' + parentId + '/builder/' + id;

		return this.httpClient.delete(this._url)
			.map(this.handleData)
			.catch(this.handleError);
	}

	private handleData(res: Response) {
		return res;
	}

	private handleError(error: Response | any) {
		return Observable.throw(error);
	}
}