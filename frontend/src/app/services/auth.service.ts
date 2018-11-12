import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Response, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';

import { AppConfig } from '@config/app.config';

@Injectable()
export class AuthService {
    _url: string;
    
    constructor(private config: AppConfig,
                private httpClient: HttpClient) { }

    postLogin(item: {}): Observable<{}> {
        this._url = this.config.getConfig('api') + '/oauth/token';
        
        return this.httpClient.post(this._url, item)
            .map(this.handleData)
            .catch(this.handleError);
    }

    postRegister(item: {}): Observable<{}> {
        this._url = this.config.getConfig('api') + '/api/register';

        return this.httpClient.post(this._url, item)
            .map(this.handleData)
            .catch(this.handleError);
    }

    private handleData(res: Response) {
        return res;
    }

    private handleError(error: Response | any) {
        return Observable.throw(error);
    }


    get authenticated(): boolean {
    	const expiresAt = JSON.parse(localStorage.getItem('expires_in'));
    	return Date.now() < expiresAt;
    }


}
