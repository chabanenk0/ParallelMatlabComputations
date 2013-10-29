function calculate_task(measurefunction,infilename,wind,tendwind,resid)
% Функция рассчета оконной меры
% measurefunction - текст вызова функции рассчета меры (используйте
%     переменную y_fragment как входной массив значений окна для рассчета
% infilename - входной файл
% wind - окно (целое число
% tendwind - позиция последнего значения окна
% resid - id результирующего временного ряда

y=dlmread(infilename);
if ((tendwind-wind>0)&&(tendwind<length(y)))
    y_fragment=y(tendwind-wind:tendwind);
    eval(measurefunction);
    send_task_result(resid,tendwind,c1,c2,c3);
else
    error;
end