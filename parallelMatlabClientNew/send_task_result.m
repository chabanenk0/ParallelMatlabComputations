function send_task_result(resid,tendwin,c1,c2,c3);
% Функція відправляє на сервер результати розрахунку вікна
server='http://127.0.0.1:80/';
%server='http://prognoz.ck.ua:80/';
%resultseriesdata.php?action=new
% post parameters: lastdate, lasttime, name, type, c1,c2,c3...
url=strcat(server,['TS/resultseriesdata.php?action=new']);
params = {'resultid',num2str(resid), 'position',num2str(tendwin),'c1',num2str(c1),...
    'c2',num2str(c2),'c3',num2str(c3),'doitdas4dsa454a6s54da65s4a6s5d4a6s5','1'}; 
data=urlread(url,'POST',params);
%[taskid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
%data=data(NEXTINDEX:length(data));
%[taskgroupid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
%data=data(NEXTINDEX:length(data));
