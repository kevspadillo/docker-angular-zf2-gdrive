import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { GoogleUploadModule } from '../google-upload/google-upload.module';

@NgModule({
    declarations: [
        AppComponent
    ],
    imports: [
        BrowserModule,
        GoogleUploadModule
    ],
    providers: [],
    bootstrap: [AppComponent]
})

export class AppModule { }