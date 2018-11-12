import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import { HttpClient } from '@angular/common/http';
import { Response, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';

import { AppConfig } from '../config/app.config';

@Injectable()
export class RefreshService {
	_url: string;
	_item: Object = {};

	constructor(private config: AppConfig,
				private httpClient: HttpClient,
				private http: Http) { }

	refreshToken(): Observable<any> {

		this._url = this.config.getConfig('api') + '/oauth/token';

		this._item = {
			refresh_token: localStorage.getItem('refresh_token'),
			client_id: 2,
			client_secret: this.config.getConfig('client_secret'),
			grant_type: 'refresh_token'
		}
		
		return this.http.post(this._url, this._item)
            .map(this.handleData)
            .catch(this.handleError);
	}

	private handleData(response: any) {
		return response;
	}

	private handleError(error: Response | any) {
		return Observable.throw(error);
	}
}