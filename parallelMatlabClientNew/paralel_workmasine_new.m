function paralel_workmasine_new;
% Программа, работающая на рабочей машине (запускается на каждой
% машине/ядре)
servershare=' \\\\192.168.2.120\\SharedDocs';
%serverweb='http://prognoz.sys-admin.dp.ua:80';
serverweb='http://prognoz.ck.ua:80';
%serverweb='http://127.0.0.1:80';
%serverweb='http://192.168.2.168:80';
taskpath='F:\!diskd\work_matlab\PredictionBenchmark\20110704_MarkovNewalgorithm_testlen\';
global path_to_rar;
if (isempty(path_to_rar))
    path_to_rar='C:\Program Files\WinRAR\';
end
path_to_zip='';


global server_data_url;
server_data_url='ftp://prognoz.sys-admin.dp.ua/pub/';
global path_to_wget;
path_to_wget='wget\';
global path_to_curl;
path_to_curl='curl\';
%uid=1;% идентификатор пользователя, который 
%methodsfolder='C:\Users\Андрей\Dropbox\ParallelMatlabServer\ParallelMatlab20MatlabClient\';
%methodsfolder='d:\20120611\ParallelMatlab20MatlabClient\';
%datafolder =  'C:\Users\Андрей\Dropbox\ParallelMatlabServer\ParallelMatlab20MatlabClient\data\';
%datafolder = 'd:\20120611\ParallelMatlab20MatlabClient\data\';

%methodsfolder='D:\dropboxfolder\Dropbox\ParallelMatlabServer\ParallelMatlab20MatlabClient\';
%datafolder =  'D:\dropboxfolder\Dropbox\ParallelMatlabServer\ParallelMatlab20MatlabClient\data\';
methodsfolder=pwd;
datafolder=[methodsfolder,'data\'];

addpath(pwd);

lasttaskgroupid=0;
taskgroupid=0;
lastdataid=0;
lasttaskid=0;

while 1
    try
        [uid,pid]=get_uid_pid();
        data=urlread(strcat(serverweb,['/ParalelMatlabServer2/processreg.php?uid=' num2str(uid) '&platformid=1','&pid=' num2str(pid)]));
        [id,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
        id=id(1);
        if (pid ~=id)
            someundefinitevariable;% способ вылететь в секцию catch ;) использовать несущ. переменную
        end
    catch
        uid=input('Enter your user ID (number of the user in users table):');
        data=urlread(strcat(serverweb,['/ParalelMatlabServer2/processreg.php?uid=' num2str(uid) '&platformid=1']));
        [id,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
        id=id(1);
        pid=id;
        fp01=fopen('get_uid_pid.m','w');
        fprintf(fp01,'function [uid,pid]=get_uid_pid();\n');
        fprintf(fp01,'%%This m-file saves uid and pid for this machine\n\n');
        fprintf(fp01,'uid=%d;\n',uid);
        fprintf(fp01,'pid=%d;\nend\n',pid);
        fclose(fp01);
        rehash;
    end
data=data(NEXTINDEX:length(data));
[num_res,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
data=data(NEXTINDEX:length(data));
curdir=pwd;
cd (methodsfolder);
for i=1:num_res
    %name=['method' num2str(i)];
    [methodid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
    %id=id(1);
    data=data(NEXTINDEX:length(data));
    [name,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%s',1);
    data=data(NEXTINDEX:length(data));
    % name=data(i,2);
    try
       cd(name);
       addpath(pwd);
       cd ('..');
    catch
        urlwrite(strcat(serverweb,['/ParalelMatlabServer2/methodget.php?id=' num2str(methodid)]),[name '.rar']);
        mkdir(name);        
        system(['"' path_to_rar 'rar" e ' name '.rar *.* ' name '/']);
        cd (name);
        % распаковываем и добавляем путь...
        addpath(pwd);
        cd ('..');
    end
end


%cd(methodsfolder);
if (isdir(datafolder))
    cd(datafolder);
else
    cd(methodsfolder);
    mkdir('data'); %if you have different location you should either create directories manually or rewrite this actions
end


%cd(taskpath);
    %addpath(genpath('m:\'));
    %rehash toolbox;
    %data = wwwread(strcat(serverweb,'/ParalelMatlabServerNew/gettask.php'));
    data=urlread([serverweb '/ParalelMatlabServer2/gettask.php?taskgroupid=' num2str(taskgroupid)]);

    [num_task,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
    if(length(data)>0)
    data=data(NEXTINDEX:length(data));
    lastenter=strfind(data,sprintf('%c','\n'));
    udata=uint8(data);
    lastenter=find(udata==10); % 20130111 было find(udata==13) но из виндового сервера идет последовательность кодов 32 10 для ентера.
    if (length(lastenter)>0)
        lastenter=lastenter(2); % 201340111 когда искали код 13, было lastenter(1) корректирую на (2) бо там еще ентеры есть...
        NEXTINDEX=lastenter+1;
        %[command,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%s',1);
        command=data(1:lastenter);
        COUNT=lastenter;
        data=data(NEXTINDEX:length(data));
        [datafile,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%s',1);
        lastdatafile=datafile;
        data=data(NEXTINDEX:length(data));
        [dataid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
        lastdataid=dataid;
        data=data(NEXTINDEX:length(data));
        [taskgroupid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
        lasttaskgroupid=taskgroupid;
        data=data(NEXTINDEX:length(data));
        [taskgroupdata,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
        emptytaskflag=0;
        lasttaskid=num_task;
    else
        emptytaskflag=1;
    end
    else
        emptytaskflag=1;
    end
    if (~emptytaskflag)
        
    else
        num_task=[];
        % нужно добавить возобновление старой недосчитанной задачи
        % 20130116 достало. Решил выключить всю эту хренотень, потом
        % когда-то отлажу. Для включения нужно убрать следующие 3 строки:
        % начало
        taskgroupid=lasttaskgroupid;
        dataid=lastdataid;
        %lastdatafile='';
        % конец

        try
            taskgroupid;%=dataid; % проверка существования curdataid
            dataid;
        catch
            try
                [taskgroupid,lastdatafile]=get_saved_taskgroupid();
                dataid=curdataid;
            catch
                taskgroupid=0;
                dataid=0;
                lastdatafile='';
            end
        end
    end
    if isempty(num_task)
        if (taskgroupid~=0)
            % сюда вписать запакование и отправку...
            cd (lastdatafile);
            load('dir.mat');
            archfilename=[lastdatafile '_computed_pid_' num2str(id) '_taskgroupid_' num2str(taskgroupid) '.rar'];
            remove_jscripts;
            archivenewfiles(filesexcept,archfilename);
            sendnewfiles(serverweb,archfilename,id,curdataid,num_task,uid,taskgroupid);
            % Закомментировал, бо отправляет 100мб файлы. Нужно отлаживать.
            s=urlread(strcat(serverweb,strcat('/ParalelMatlabServer2/done.php?result=1&id=',num2str(lasttaskid))));
            curdataid=0;
            
            dataid=0;
            lasttaskgroupid=0;
            taskgroupid=0;
            lastdataid=0;
            cd ('..')
            if(0)
            fid11=fopen('get_saved_taskgroupid.m','w');
            fprintf(fid11,'function [c,d,f]=get_saved_curdataid();\nc=%d;\nd=%d;\nf='';\n',taskgroupid,dataid);
            fclose(fid11);
            end
            continue;
        else
            curdataid=dataid;
            if (0)
            fid11=fopen('get_saved_curdataid.m','w');
            fprintf(fid11,'function [c,d,f]=get_saved_curdataid();\nc=%d;\nd=%d;\nf=%c%s%c\n',taskgroupid,dataid,39,lastdatafile,39);
            fclose(fid11);
            end
            fprintf(1,'Waiting for a new job at server.\nPause 10 seconds...');
            pause(1);
            continue;
        end
    else 
        index=num_task;
        curdataid=dataid;
        %command=data{2};
        fprintf(1,'Got a new job # %d\n',index);
        fprintf(1,'Executing command: %s\n',command);
    end
    % нужно переместить эту процедуру в пхп-часть. 
    lastslash=find(datafile=='/');
    if (length(lastslash)>0)
        lastslash=lastslash(length(lastslash));
        datafile=datafile(lastslash+1:length(datafile));
    end
    rarmode=1;
    if (strfind(datafile,'.zip')>0)
        rarmode=0;% zipmode
    end
    if (rarmode)
        datafile=strrep(datafile,'.rar','');
    else
        datafile=strrep(datafile,'.zip','');
    end
    lastdatafile=datafile;

    %data=data2cell(data);
    try
       cd(datafile);
       %addpath(pwd);
       %cd ('..');
    catch
        if (rarmode)
            arch_extension='.rar'
        else
            arch_extension='.zip'
        end
        
        urlwrite(strcat(serverweb,['/ParalelMatlabServer2/dataget.php?id=' num2str(dataid)]),[datafile arch_extension]);
        mkdir(datafile);
        if (rarmode)
            system(['"' path_to_rar 'rar" e ' datafile arch_extension ' *.* ' datafile '/']);
        else
            %system(['"' path_to_zip 'unzip" ' datafile arch_extension ' -d ' datafile '/']);
            unzip([datafile arch_extension],[datafile '/']);
        end
        cd(datafile);
        filesexcept=dir;
        save('dir.mat','filesexcept');
    end
    %$commandstring="unzip ".$onlyfilename." -o -d ".$newfolder." >nul";
    
    if (taskgroupdata~=0)
        data222=urlread([serverweb '/ParalelMatlabServer2/getadddata.php?taskgroupid=' num2str(taskgroupdata)]);
        [adddataid,COUNT2,ERRMSG2,NEXTINDEX2]=sscanf(data222,'%d',1);
        urlwrite(strcat(serverweb,['/ParalelMatlabServer2/dataresget.php?id=' num2str(adddataid)]),[datafile '_add.rar']);
        %system(['"' path_to_rar 'rar" e ' datafile '_add.rar *.* ' datafile '/']);
        unzip([datafile '_add.rar'],'./');
        filesexcept=dir;
        save('dir.mat','filesexcept');
    end
    %cd (datafile);
    % распаковываем и добавляем путь...
    %addpath(pwd);
    %cd ('..');
    
    
    try
        global par_tasknum;
        par_tasknum=index;
        eval(command);
        %s=wwwread(strcat(serverweb,strcat('/ParalelMatlabServerNew/done.php?result=1&id=',num2str(index))));
        s=urlread(strcat(serverweb,strcat('/ParalelMatlabServer2/done.php?result=1&id=',num2str(index))));
        fprintf(1,'Execution ok.\n');
    catch
        %s=wwwread(strcat(serverweb,strcat('/ParalelMatlabServerNew/done.php?result=0&id=',num2str(index))));
        s=urlread(strcat(serverweb,strcat('/ParalelMatlabServer2/done.php?result=0&id=',num2str(index))));
        fprintf(1,'Execution failed.\n');
    end
    clear global par_tasknum;
    %data=yahoo(serverweb);
    %data=fetch(sock,'d');
    %close(sock);
    cd('..');
    fprintf(1,'Press any key...');
    %pause;
end


%system(['"' path_to_rar '"rar a ' filename ' ' addfiles ]);